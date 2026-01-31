<?php

namespace App\Http\Controllers;
use App\Exports\Purchase_detailExport;
use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\PurchaseOrder;
use App\Models\Purchase_detail;
use App\Models\Producto;
use App\Models\Setting;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PurchaseOrder::with(['seller', 'user', 'purchasedetails.producto']);

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
                       
                        $sub->whereHas('seller', function ($q2) use ($term) {
                            $q2->where('nombres', 'like', $term)
                                ->orWhere('apellidos', 'like', $term)
                                ->orWhere('numidentificacion', 'like', $term);
                        })                           
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
                'datosCompra.tipo_compra' => 'required',
                'itemselected' => 'required|array|min:1',
                'itemselected.*.precio_compra' => 'required|numeric|min:0',
                'itemselected.*.precio_venta' => 'required|numeric|min:0',
                'itemselected.*.cantidad' => 'required',
            ],
            [],
            [
                'datosCompra.seller_id' => 'proveedor',
                'itemselected' => 'productos seleccionados',
                'itemselected.*.precio_compra' => 'precio de compra',
                'itemselected.*.precio_venta' => 'precio de venta',
                'itemselected.*.cantidad' => 'cantidad'
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
                'tipo_compra' => $request->input('datosCompra.tipo_compra'),
                'status' => $request->input('datosCompra.status'),
                'total' => 0, // Inicialmente 0, se actualizará después
            ]);

            // Crear los detalles de la orden
            $total = 0;
            foreach ($request->input('itemselected') as $producto) {
                $productoDB = Producto::with(['iva', 'ibua', 'ipc'])->findOrFail($producto['id']);
                //$precioUnitario = $productoDB->precio_compra;

                $stock = $productoDB->stock;
                $preciocompra = $producto['precio_compra'];
                $precioventa = $producto['precio_venta'];
                $ganancia = $producto['ganancia'];
                $cantidad = $producto['cantidad'];
                $precio_final = $producto['precio_final'];
                $iva = $productoDB->iva ? floatval($productoDB->iva->valor) : 0;
                $ibua = $productoDB->ibua ? floatval($productoDB->ibua->valor) : 0;
                $ipc = $productoDB->ipc ? floatval($productoDB->ipc->valor) : 0;

                // Actualizar stock
                $productoDB->update(['stock' => $stock + $cantidad]);
                $productoDB->update(['precio_compra' => $preciocompra]);
                $productoDB->update(['precio_venta' => $precioventa]);
                $productoDB->update(['ganancia' => $ganancia]);
                $productoDB->update(['precio_final' => $precio_final]);
                $subtotal = $preciocompra * $cantidad;
                $ivaMonto = ($iva !== null) ? ($subtotal * ($iva / 100)) : 0;
                $ibuaMonto = ($ibua !== null) ? ($subtotal * ($ibua / 100)) : 0;
                $ipcMonto = ($ipc !== null) ? ($subtotal * ($ipc / 100)) : 0;
                $totalProducto = $subtotal + $ivaMonto + $ibuaMonto + $ipcMonto;

                // Guardar el detalle de la orden
                Purchase_detail::create([
                    'purchase_id' => $purchase->id,
                    'producto_id' => $productoDB->id,
                    'cantidad' => $cantidad,
                    'precio_unitario' => $preciocompra,
                    'iva' => $ivaMonto,
                    'ibua' => $ibuaMonto,
                    'ipc' => $ipcMonto
                ]);

                $total += $totalProducto;

            }
            $tipoCompra = $request->input('datosCompra.tipo_compra');

            if($tipoCompra === 'Gasto'){
                Expense::create([
                    'user_id' => auth()->id(),
                    'purchase_order_id' => $purchase->id,
                    'tipo_gasto' => 'Compra Articulos',
                    'monto' => $total,
                    'fecha' => $purchase->order_date,
                    'descripcion' => 'Factura de compra N° ' . $purchase->factura_numero,
                ]);
            }
                      
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

    public function compraSearch(string $factura_numero)
    {
        $compra = PurchaseOrder::with(['purchasedetails.producto', 'seller', 'user'])
        ->where('factura_numero', $factura_numero)->first();
    
        return response()->json($compra);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,string $id)
    {
        $purchaseOrder = PurchaseOrder::find($id);

        $request->validate([
            'status' => 'required|string'
        ]);

        $purchaseOrder->update(['status' => $request->status]);

        return response()->json(['message' => 'Estado actualizado correctamente', 'data' => $purchaseOrder]);
    }
  

    public function compraPDF(string $id)
    {
        $negocio = Setting::first();
        $orden = PurchaseOrder::with(['purchasedetails.producto', 'seller', 'user'])->find($id);
        $pdf = Pdf::loadView('ordenescompra.comprapdf', compact('orden', 'negocio'));

        return $pdf->stream('compra.pdf');
    }

    public function exportPurchases(Request $request)
    {

        $fechaini = Carbon::parse($request->input('fechaini'))->startOfDay();
        $fechafin = Carbon::parse($request->input('fechafin'))->endOfDay();
        return Excel::download(new Purchase_detailExport($fechaini, $fechafin), 'compras_' . now()->format('Ymd_His') . '.xlsx');
    }

}    
