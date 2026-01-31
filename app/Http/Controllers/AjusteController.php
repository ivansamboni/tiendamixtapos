<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Producto;
use App\Models\Ajuste;
use App\Models\Ajuste_detail;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Setting;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class AjusteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ajuste = Ajuste::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        return response()->json($ajuste);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar los datos recibidos
        $usuarioId = auth()->id();
        $fecha = now()->format('dm');
        $ultimoNumero = Ajuste::max('id') + 1;

        $request->validate(
            [
                'datosCompra.user_id' => 'required',
                'itemselected' => 'required|array|min:1',
            ],
            [],
            [
                'itemselected' => 'productos seleccionados'
            ]
        );

        try {
            // Iniciar una transacción
            DB::beginTransaction();

            // Crear la orden
            $ajuste = Ajuste::create([
                'factura_numero' => sprintf("00%d%s%03d", $usuarioId, $fecha, $ultimoNumero),
                'user_id' => $request->input('datosCompra.user_id'),
                'order_date' => now(),
                'status' => $request->input('datosCompra.status'),
                'descripcion' => $request->input('datosCompra.descripcion'),
            ]);
            // Crear los detalles de la orden

            foreach ($request->input('itemselected') as $producto) {
                $productoDB = Producto::findOrFail($producto['id']);
                //$precioUnitario = $productoDB->precio_compra;             
               
                $stock_nuevo = $producto['stock'];
                $stock_cambio = $producto['stock_cambio'];

                // Actualizar stock
                $productoDB->update(['stock' => $stock_nuevo]);
               
                // Guardar el detalle de la orden
                Ajuste_detail::create([
                    'ajuste_id' => $ajuste->id,
                    'producto_id' => $productoDB->id,
                    'stock_cambio' => $stock_cambio
                ]);
            }

            // Confirmar la transacción
            DB::commit();

            return response()->json([
                'message' => 'Venta creada con éxito',
                'ticket_url' => url("/ajustepdf/{$ajuste->id}") // URL para abrir el PDF
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'error' => 'No se pudo completar',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $orden = Ajuste::with(['ajustedetails.producto', 'user'])->find($id);

        return response()->json($orden);
    }
    public function ajustePDF($id)
    {
        $negocio = Setting::first();
        $orden = Ajuste::with(['ajustedetails.producto', 'user'])->find($id);
        $pdf = Pdf::loadView('facturas.ajustepdf', compact('orden', 'negocio'));

        return $pdf->stream('ajuste.pdf');
    }
    public function filtroFecha(Request $request)
    {
        $request->validate([
            'fechaini' => 'required|date',
            'fechafin' => 'required|date|after_or_equal:fechaini',
        ]);

        $fechaini = $request->input('fechaini');
        $fechafin = $request->input('fechafin');

        $query = Ajuste::whereBetween('order_date', [$fechaini, $fechafin])
            ->orderBy('order_date', 'desc')
            ->with('user')->paginate(20);

        return response()->json($query);
    }
}
