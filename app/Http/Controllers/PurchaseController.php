<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\PurchaseOrder;
use App\Models\Purchase_detail;
use App\Models\Producto;
use App\Models\Setting;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\Rule;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $purchase = PurchaseOrder::with(['seller', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        return response()->json($purchase);
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        // Validar los datos recibidos
        $request->validate(
            [
                'datosCompra.factura_numero' => [
                    'required',
                    Rule::unique('purchase_orders', 'factura_numero')
                ],
                'datosCompra.seller_id' => 'required',
                'datosCompra.user_id' => 'required',
                'itemselected' => 'required|array|min:1',
            ],
            [],
            [
                'datosCompra.seller_id' => 'proveedor',
                'itemselected' => 'productos seleccionados'
            ]
        );

        try {
            // Iniciar una transacción
            DB::beginTransaction();

            // Crear la orden
            $purchase = PurchaseOrder::create([
                'factura_numero' => $request->input('datosCompra.factura_numero'),
                'user_id' => $request->input('datosCompra.user_id'),
                'seller_id' => $request->input('datosCompra.seller_id'),
                'order_date' => now(),
                'tipo_pago' => $request->input('datosCompra.tipo_pago'),
                'status' => $request->input('datosCompra.status'),
                'total' => 0, // Inicialmente 0, se actualizará después
            ]);

            // Crear los detalles de la orden
            $total = 0;
            foreach ($request->input('itemselected') as $producto) {
                $productoDB = Producto::findOrFail($producto['id']);
                //$precioUnitario = $productoDB->precio_compra;
                $iva = floatval($productoDB->iva);
                $stock = $productoDB->stock;
                $preciocompra = $producto['precio_compra'];
                $precioventa = $producto['precio_venta'];
                $cantidad = $producto['cantidad'];
                $iva = $producto['iva'];

                // Actualizar stock
                $productoDB->update(['stock' => $stock + $cantidad]);
                $productoDB->update(['precio_compra' => $preciocompra]);
                $productoDB->update(['precio_venta' => $precioventa]);
                $productoDB->update(['iva' => $iva]);
                $subtotal = $preciocompra * $cantidad;
                $ivaMonto = ($iva !== null) ? ($subtotal * ($iva / 100)) : 0;
                $totalProducto = $subtotal + $ivaMonto;

                // Guardar el detalle de la orden
                Purchase_detail::create([
                    'purchase_id' => $purchase->id,
                    'producto_id' => $productoDB->id,
                    'cantidad' => $cantidad,
                    'precio_unitario' => $preciocompra,
                    'iva' => $ivaMonto,
                ]);

                // Calcular el total acumulado
                $total += $totalProducto;
            }

            // Actualizar el total en la orden
            $purchase->update(['total' => $total]);

            // Confirmar la transacción
            DB::commit();

            return response()->json([
                'message' => 'Venta creada con éxito',
                'ticket_url' => url("/ticket/{$purchase->id}") // URL para abrir el PDF
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'error' => 'No se pudo completar la venta',
                'message' => $e->getMessage()
            ], 400);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $orden = PurchaseOrder::with(['purchasedetails.producto', 'seller', 'user'])->find($id);

        return response()->json($orden);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    public function filtroFecha(Request $request)
    {
        $request->validate([
            'fechaini' => 'required|date',
            'fechafin' => 'required|date|after_or_equal:fechaini',
        ]);
        
        $fechaini = $request->input('fechaini');
        $fechafin = $request->input('fechafin');

        $query = PurchaseOrder::whereBetween('order_date', [$fechaini, $fechafin])
        ->orderBy('order_date', 'desc')
        ->with(['seller', 'user'])->paginate(20);

        return response()->json($query);
    }

    public function compraPDF($id)
    {
        $negocio = Setting::first();
        $orden = PurchaseOrder::with(['purchasedetails.producto', 'seller', 'user'])->find($id);
        $pdf = Pdf::loadView('ordenescompra.comprapdf', compact('orden', 'negocio'));

        return $pdf->stream('compra.pdf');
    }
}
