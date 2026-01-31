<?php

namespace App\Http\Controllers;
use App\Events\VentaRealizada;
use App\Exports\Sale_detailExport;
use App\Jobs\EnviarFacturaClienteJob;
use App\Mail\VentaRealizadaMailable;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Producto;
use App\Models\Sale;
use App\Models\Credit;
use App\Models\Caja;
use App\Models\Setting;
use App\Models\Sale_detail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;


class SaleController extends Controller
{

    public function index(Request $request)
    {
        $query = Sale::with(['client', 'user']);

        if ($request->filled('fechaini') && $request->filled('fechafin')) {

            $request->validate([
                'fechaini' => ['date'],
                'fechafin' => ['date', 'after_or_equal:fechaini'],
            ]);

            $fechaini = Carbon::parse($request->fechaini)->startOfDay();
            $fechafin = Carbon::parse($request->fechafin)->endOfDay();

            $query->whereBetween('created_at', [$fechaini, $fechafin]);
        }


        if ($request->filled('search')) {
            $search = trim($request->search);
            $terms = array_filter(explode(' ', $search));

            $query->where(function ($q) use ($terms) {
                foreach ($terms as $term) {
                    $term = "%{$term}%";

                    $q->where(function ($sub) use ($term) {

                        // Cliente
                        $sub->whereHas('client', function ($q2) use ($term) {
                            $q2->where('nombres', 'like', $term)
                                ->orWhere('apellidos', 'like', $term)
                                ->orWhere('numidentificacion', 'like', $term);
                        })
                            // Venta
                            ->orWhere('factura_numero', 'like', $term);                            
                    });
                }
            });
        }

        $ventas = $query
            ->orderByDesc('created_at')
            ->paginate(50);

        return response()->json($ventas);
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clients,id',
            'forma_pago' => 'required|in:1,2',
            'metodo_pago' => 'nullable|string',
            'items' => 'required|array|min:1',
        ]);

        try {
            $usuarioId = auth()->id();
            $fecha = now()->format('dm');
            $ultimoNumero = Sale::max('id') + 1;

            // Buscar caja abierta del usuario
            $caja = Caja::where('user_id', $usuarioId)
                ->whereNull('cierre')
                ->latest()
                ->first();
            $cajaId = $caja?->id;

            // Crear la venta
            $sale = Sale::create([
                'factura_numero' => sprintf("00%d%s%03d", $usuarioId, $fecha, $ultimoNumero),
                'cliente_id' => $request->cliente_id,
                'user_id' => $usuarioId,
                'caja_id' => $caja?->id,
                'forma_pago' => $request->forma_pago,
                'metodo_pago' => $request->forma_pago === '1'
                    ? $request->metodo_pago
                    : null,
                'total' => 0,
            ]);

            $total = 0;

            foreach ($request->items as $producto) {

                if (empty($producto['id']) || empty($producto['cantidad'])) {
                    continue;
                }
    
                $productoDB = Producto::with(['iva', 'ibua', 'ipc'])->find($producto['id']);
                if (!$productoDB) continue;
    
                $cantidad = $producto['cantidad'];
                $precioUnitario = $productoDB->precio_venta;
    
                // Impuestos
                $iva  = $productoDB->iva  ? floatval($productoDB->iva->valor)  : 0;
                $ibua = $productoDB->ibua ? floatval($productoDB->ibua->valor) : 0;
                $ipc  = $productoDB->ipc  ? floatval($productoDB->ipc->valor)  : 0;
    
                // Stock
                $productoDB->decrement('stock', $cantidad);
    
                $base = $precioUnitario * $cantidad;
                $ivaMonto  = $base * ($iva / 100);
                $ibuaMonto = $base * ($ibua / 100);
                $ipcMonto  = $base * ($ipc / 100);
    
                $subtotal = $base + $ivaMonto + $ibuaMonto + $ipcMonto;
    
                Sale_detail::create([
                    'sale_id'         => $sale->id,
                    'producto_id'     => $productoDB->id,
                    'cantidad'        => $cantidad,
                    'precio_unitario' => $precioUnitario,
                    'iva'             => $ivaMonto,
                    'ibua'            => $ibuaMonto,
                    'ipc'             => $ipcMonto,
                ]);
    
                $total += $subtotal;
            }

            // ðŸ”¹ Redondear total a mÃºltiplo de 50
            $total = round($total / 50) * 50;
            $sale->update(['total' => $total]);
           
            // Registrar crÃ©dito si aplica
            if ($sale->forma_pago === '2') {
                Credit::create([
                    'sale_id'       => $sale->id,
                    'total_credito' => $total,
                    'saldo'         => $total,
                    'fecha_limite'  => now()->addDays(30),
                ]);
            }
            // Actualizar caja solo si existe
            if ($sale->forma_pago === '1' && $caja) {
                $caja->increment('monto_final', $total);
            }

             // Enviar factura por correo (si existe cliente y correo)
             if ($sale->client && !empty($sale->client->email)) {
                EnviarFacturaClienteJob::dispatch($sale);
            }

            return response()->json([
                'message' => 'Venta creada con Ã©xito',
                'ticket_url' => url("/ticket/{$sale->uuid}"),
            ]);

        } catch (\Exception $e) {
            // ðŸ”¹ Capturar cualquier error inesperado
            \Log::error('Error en venta: ' . $e->getMessage());

            return response()->json([
                'message' => 'Error al crear la venta',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function generarTicket($uuid)
    {
        $negocio = Setting::first();
        $orden = Sale::with(['details.producto', 'client', 'user'])
            ->where('uuid', $uuid)
            ->firstOrFail();

        return view('facturas.ticketFactura', compact('orden', 'negocio'));
    }


    public function facturaPDF($uuid)
    {
        $negocio = Setting::first();
        $orden = Sale::with(['details.producto', 'client', 'user'])->where('uuid', $uuid)->firstOrFail();
        $pdf = Pdf::loadView('facturas.facturaPdf', compact('orden', 'negocio'));

        return $pdf->stream('ticket.pdf');
    }

    public function enviarFactura($uuid)
    {
        $orden = Sale::with(['details.producto', 'client', 'user'])->where('uuid', $uuid)->firstOrFail();
        $negocio = Setting::first();

        $pdf = Pdf::loadView('facturas.facturaPdf', compact('orden', 'negocio'))->output();

        Mail::to($orden->client->email)->send(new VentaRealizadaMailable($orden, $negocio, $pdf));

        return response()->json(['mensaje' => 'Factura enviada correctamente']);
    }


    public function show($id)
    {
        $orden = Sale::with(['details.producto', 'client', 'user'])->find($id);

        return response()->json($orden);
    }

    public function exportSales(Request $request)
    {
        return Excel::download(
            new Sale_detailExport(
                $request->input('fechaini'),
                $request->input('fechafin'),
                $request->input('id')
            ),
            'ventas_' . now()->format('Ymd_His') . '.xlsx'
        );
    }
}