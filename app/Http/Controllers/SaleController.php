<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use DB;
use Symfony\Component\HttpFoundation\Response;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Producto;
use App\Models\Sale;
use App\Models\Setting;
use App\Models\Sale_detail;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with(['client', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        return response()->json($sales);
    }

    public function store(Request $request)
    {
        // Validar los datos recibidos
        $request->validate([
            'datosVenta.cliente_id' => 'required',
            'itemsVenta' => 'required',

        ]);

        // Crear la orden
        $sale = Sale::create([
            'cliente_id' => $request->input('datosVenta.cliente_id'),
            'user_id' => $request->input('datosVenta.user_id'),
            'tipo_pago' => $request->input('datosVenta.tipo_pago'),
            'impuesto' => $request->input('datosVenta.impuesto'),
            'total' => 0, // Inicialmente 0, se actualizará después
        ]);

        // Crear los detalles de la orden
        $total = 0;
        foreach ($request->input('itemsVenta') as $producto) {
            $productoDB = Producto::findOrFail($producto['id']);
            $precioUnitario = $productoDB->precio_venta;
            $iva = floatval($productoDB->iva);
            $stock = $productoDB->stock;
            $cantidad = $producto['cantidad'];
            $stockActual = $stock - $cantidad;
            $productoDB->update(['stock' => $stockActual]);
            $subtotal = $precioUnitario * $cantidad;
            $ivaMonto = ($iva !== null) ? ($subtotal * ($iva / 100)) : 0;
            $totalProducto = $subtotal + $ivaMonto;

            // Guardar el detalle de la orden
            Sale_detail::create([
                'sale_id' => $sale->id,
                'producto_id' => $productoDB->id,
                'cantidad' => $cantidad,
                'precio_unitario' => $precioUnitario,
                'iva' => $ivaMonto,
            ]);

            // Calcular el total acumulado
            $total += $totalProducto;
        }
        $resto = $total % 100;

        // Si el resto es mayor a 50, redondeamos a la siguiente centena
        if ($resto > 50) {
            $totalRedondeado = ceil($total / 100) * 100;
        } else {
            // Si el resto es 50 o menor, lo dejamos en la centena actual
            $totalRedondeado = floor($total / 100) * 100 + 50;
        }
        // Actualizar el total en la orden
        $sale->update(['total' => $totalRedondeado]);


        return response()->json([
            'message' => 'Venta creada con éxito',
            'ticket_url' => url("/ticket/{$sale->id}") // URL para abrir el PDF
        ]);

        //return response($orden, Response::HTTP_CREATED);
    }

    public function generarTicket($id)
    {
        $negocio = Setting::first();
        $orden = Sale::with(['details.producto', 'client', 'user'])->find($id);
        $pdf = Pdf::loadView('facturas.ticketFactura', compact('orden', 'negocio'))
            ->setPaper([0, 0, 226.77, 841.89]); // Tamaño de 58mm de ancho en puntos

        return $pdf->stream('ticket.pdf');

    }

    public function facturaPDF($id)
    {
        $negocio = Setting::first();
        $orden = Sale::with(['details.producto', 'client', 'user'])->find($id);
        $pdf = Pdf::loadView('facturas.facturaPDF', compact('orden', 'negocio'));

        return $pdf->stream('ticket.pdf');
    }

    public function show($id)
    {
        $orden = Sale::with(['details.producto', 'client', 'user'])->find($id);

        return response()->json($orden);
    }

    public function ventasStats()
    {

        $dia = Sale::whereDate('created_at', today())
            ->with('details.producto') // Carga la relación
            ->get()
            ->flatMap(function ($sale) {
                return $sale->details->map(function ($detail) {
                    return $detail->producto->ganancia * $detail->cantidad;
                });
            })->sum();

        $semana = Sale::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->with('details.producto')
            ->get()
            ->flatMap(fn($sale) => $sale->details->map(fn($detail) => $detail->producto->ganancia * $detail->cantidad))
            ->sum();

        $mes = Sale::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->with('details.producto')
            ->get()
            ->flatMap(fn($sale) => $sale->details->map(fn($detail) => $detail->producto->ganancia * $detail->cantidad))
            ->sum();

        $topProductos = Sale_detail::select('producto_id', DB::raw('SUM(cantidad) as total_vendido'))
            ->groupBy('producto_id') // Agrupa por producto
            ->orderByDesc('total_vendido') // Ordena de mayor a menor
            ->with('producto') // Carga la relación del producto
            ->limit(10) // Limita a los 10 más vendidos
            ->get();

        return response()->json([
            'gananciaHoy' => $dia,
            'gananciaSemana' => $semana,
            'gananciaMes' => $mes,
            'masVendido' => $topProductos,
        ]);

    }

    public function filtroFecha(Request $request)
    {
        $request->validate([
            'fechaini' => 'required|date',
            'fechafin' => 'required|date|after_or_equal:fechaini',
        ]);
        
        $fechaini = Carbon::parse($request->input('fechaini'))->startOfDay();
        $fechafin = Carbon::parse($request->input('fechafin'))->endOfDay(); 

        $query = Sale::whereBetween('created_at', [$fechaini, $fechafin])
        ->with(['client', 'user'])
        ->orderBy('created_at', 'desc')
        ->paginate(20);

        return response()->json($query);
    }
}