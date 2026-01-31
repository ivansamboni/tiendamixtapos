<?php

namespace App\Http\Controllers;
use App\Models\Nota_detail;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\DB;
use App\Models\NotasCompra;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotasCompraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = NotasCompra::with('purchase.seller');

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
                        $sub->whereHas('seller', function ($q2) use ($term) {
                            $q2->where('nombres', 'like', $term)
                                ->orWhere('apellidos', 'like', $term)
                                ->orWhere('numidentificacion', 'like', $term);
                        })
                            // Venta
                            ->orWhere('numero_nota', 'like', $term);                            
                    });
                }
            });
        }

        $notas = $query
            ->orderByDesc('created_at')
            ->paginate(50);

        return response()->json($notas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'datosNota.tipo' => 'required|in:credito,debito,20,22,30',
            'datosNota.motivo' => 'required',
            'datosNota.purchase_id' => 'required',
            'datosNota.fecha_emision' => 'required',
            'datosNota.seller_id' => 'required',
            //'datosNota.factura_numero' => 'required',
            'datosNota.correction_concept_code' => 'required|integer', // Nuevo
            'datosNota.payment_method_code' => 'required',           // Nuevo
            'datosNota.numbering_range_id' => 'required|integer',    // Nuevo
            //'datosNota.bill_id_factus' => 'required',                // Nuevo
            'itemselected' => 'required|array|min:1',
            'itemselected.*.id' => 'required|exists:productos,id',
            'itemselected.*.cantidad' => 'required|numeric|min:0.01',
        ], [], [
            'datosNota.correction_concept_code' => 'motivo DIAN',
            'datosNota.payment_method_code' => 'método de pago',
        ]);
    
        try {
            DB::beginTransaction();
    
            // 1. Generar número interno de nota (Opcional si Factus no te lo da)
            $prefijo = $request->input('datosNota.tipo') === 'credito' ? 'NC' : 'ND';
            $numero_nota = $prefijo . '-' . time(); // Ejemplo simple
    
            // 2. Crear la Nota de Compra con las nuevas columnas
            $nota = NotasCompra::create([
                'numero_nota' => "NC".$request->input('datosNota.referencia_proveedor'),
                'purchase_id' => $request->input('datosNota.purchase_id'),
                'seller_id' => $request->input('datosNota.seller_id'),
                'fecha_emision' => $request->input('datosNota.fecha_emision'),
                'referencia_proveedor' => $request->input('datosNota.referencia_proveedor'),
                'tipo' => $request->input('datosNota.tipo'),
                'motivo' => $request->input('datosNota.motivo'),
                'total' => 0,
                
                // --- NUEVOS CAMPOS TÉCNICOS ---
                'payment_method_code' => $request->input('datosNota.payment_method_code'),
                'correction_concept_code' => $request->input('datosNota.correction_concept_code'),
                'numbering_range_id' => $request->input('datosNota.numbering_range_id'),
                'bill_id_factus' => $request->input('datosNota.bill_id_factus'),
                'status' => 'draft' // Se queda en borrador hasta que Factus responda
            ]);
    
            $total = 0;
    
            foreach ($request->input('itemselected') as $item) {
                $productoDB = Producto::with(['iva', 'ibua', 'ipc'])->findOrFail($item['id']);
                
                $cantidad = floatval($item['cantidad']);
                $preciocompra = floatval($item['precio_compra']);
                
                // 3. Lógica de Stock (Inversa a la compra)
                // Si es Crédito (Devolución), el stock del negocio BAJA (le devuelves al proveedor)
                // Si es Débito (Aumento), el stock del negocio SUBE (te cobraron más productos)
                $tipo = strtolower($nota->tipo);
                $productoDB->update([
                    'stock' => DB::raw($tipo === 'credito' ? "stock - {$cantidad}" : "stock + {$cantidad}")
                ]);
    
                // 4. Cálculos de impuestos
                $iva = $productoDB->iva ? floatval($productoDB->iva->valor) : 0;
                $ibua = $productoDB->ibua ? floatval($productoDB->ibua->valor) : 0;
                $ipc = $productoDB->ipc ? floatval($productoDB->ipc->valor) : 0;
    
                $subtotal = $preciocompra * $cantidad;
                $ivaMonto = $subtotal * ($iva / 100);
                $ibuaMonto = $subtotal * ($ibua / 100);
                $ipcMonto = $subtotal * ($ipc / 100);
                
                $totalProducto = $subtotal + $ivaMonto + $ibuaMonto + $ipcMonto;
    
                // 5. Guardar detalle
                Nota_detail::create([
                    'nota_compra_id' => $nota->id,
                    'producto_id' => $productoDB->id,
                    'cantidad' => $cantidad,
                    'precio_unitario' => $preciocompra,
                    'iva' => $ivaMonto,
                    'ibua' => $ibuaMonto,
                    'ipc' => $ipcMonto,
                    'tax_rate' => $iva // Guardamos el % para facilitar el JSON de Factus
                ]);
    
                $total += $totalProducto;
            }
    
            $nota->update(['total' => $total]);
    
            DB::commit();
    
            return response()->json([
                'status' => 'success',
                'message' => 'Nota guardada localmente y stock actualizado',
                'nota_id' => $nota->id,
                'total' => $total
            ]);
    
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Error al procesar la nota',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show(string $id)
    {
        $orden = NotasCompra::with(['notadetails.producto','purchase.seller'])->find($id);

        return response()->json($orden);
    }


}
