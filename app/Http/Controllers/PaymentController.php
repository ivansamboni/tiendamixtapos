<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use App\Models\Sale;
use App\Models\Credit;
use App\Models\Payment;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Mostrar todos los créditos (opcional).
     */
    public function index(Request $request)
{
    $query = Credit::with(['sale.client']);

    if ($request->filled('search')) {
        $search = $request->search;

        // Dividir en palabras (ej: "ivan samboni" => ["ivan", "samboni"])
        $terms = array_filter(explode(' ', $search));

        $query->where(function ($q) use ($terms) {
            foreach ($terms as $term) {
                $term = "%{$term}%";

                $q->where(function ($sub) use ($term) {
                    // Coincidencias en cliente
                    $sub->whereHas('sale.client', function ($q2) use ($term) {
                        $q2->where('nombres', 'like', $term)
                           ->orWhere('apellidos', 'like', $term)
                           ->orWhere('numidentificacion', 'like', $term);
                    })
                    // Coincidencia en factura
                    ->orWhereHas('sale', function ($q2) use ($term) {
                        $q2->where('factura_numero', 'like', $term);
                    });
                });
            }
        });
    }

    // Clonar la query para sumatorias
    $totalsaldopendiente = (clone $query)->sum('saldo');
    $totalsaldocredito   = (clone $query)->sum('total_credito');

    $creditos = $query->orderBy('saldo', 'desc')->paginate(10);

    return response()->json([
        'credits' => $creditos,
        'totalsaldopendiente' => $totalsaldopendiente,
        'totalsaldocredito' => $totalsaldocredito,
    ]);
}




    /**
     * Mostrar un crédito específico.
     */
    public function show($id)
    {
        // Traes el crédito puntual con sus relaciones
        $credito = Credit::with(['sale.client', 'payments'])->findOrFail($id);

        // Obtener el cliente relacionado a ese crédito
        $clienteId = $credito->sale->client->id;

        // Traer todos los créditos de ese cliente
        $creditosCliente = Credit::with(['sale.client', 'payments'])
            ->where('saldo', '>', 0)
            ->whereHas('sale', function ($q) use ($clienteId) {
                $q->where('cliente_id', $clienteId);
            })
            ->get();

        // Calcular el total del saldo pendiente de todos sus créditos
        $totalsaldopendiente = $creditosCliente->sum('saldo');

        return response()->json([
            'credito' => $credito, // el crédito puntual
            'creditos_cliente' => $creditosCliente, // todos los créditos del cliente
            'totalsaldopendiente' => $totalsaldopendiente
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'metodo_pago' => 'required',
            'credit_id' => 'nullable|exists:credits,id', // opcional si se envía array
            'monto' => 'nullable|numeric|min:0.01',      // idem
            'creditos' => 'nullable|array',
            'creditos.*.credit_id' => 'required_with:creditos|exists:credits,id',
            'creditos.*.saldo' => 'required_with:creditos|numeric|min:0.01',
        ]);
    
        $usuarioId = auth()->id();
    
        // Buscar caja abierta del usuario
        $caja = Caja::where('user_id', $usuarioId)
            ->whereNull('cierre')
            ->latest()
            ->first();
        $cajaId = $caja?->id;
    
        try {
            DB::beginTransaction();
    
            $pagos = [];
    
            // Caso 1: array de créditos (varios pagos de una sola vez)
            if ($request->filled('creditos')) {
                foreach ($request->creditos as $item) {
                    $credit = Credit::findOrFail($item['credit_id']);
    
                    // Validar que el monto no exceda el saldo
                    if ($item['saldo'] > $credit->saldo) {
                        DB::rollBack();
                        return response()->json([
                            'message' => "El monto abonado ({$item['saldo']}) no puede ser mayor al saldo pendiente ({$credit->saldo}) del crédito {$credit->id}."
                        ], 422);
                    }
    
                    // Guardar el pago
                    $pago = Payment::create([
                        'caja_id' => $cajaId,
                        'credit_id' => $credit->id,
                        'monto' => $item['saldo'],
                        'metodo_pago' => $request->metodo_pago, // viene global
                        'fecha_abono' => now(),
                    ]);
    
                    // Actualizar saldo de crédito
                    $credit->decrement('saldo', $item['saldo']);
    
                    // Si es efectivo y hay caja -> incrementar monto_final
                    if ($caja && $pago->metodo_pago === '10') {
                        $caja->increment('monto_final', $pago->monto);
                    }
    
                    $pagos[] = [
                        'pago' => $pago,
                        'credit' => $credit->refresh()
                    ];
                }
            }
    
            // Caso 2: un solo crédito
            elseif ($request->filled('credit_id') && $request->filled('monto')) {
                $credit = Credit::findOrFail($request->credit_id);
    
                if ($request->monto > $credit->saldo) {
                    DB::rollBack();
                    return response()->json([
                        'message' => 'El monto abonado no puede ser mayor al saldo pendiente.'
                    ], 422);
                }
    
                $pago = Payment::create([
                    'caja_id' => $cajaId,
                    'credit_id' => $request->credit_id,
                    'monto' => $request->monto,
                    'metodo_pago' => $request->metodo_pago,
                    'fecha_abono' => now(),
                ]);
    
                $credit->decrement('saldo', $request->monto);
    
                if ($caja && $pago->metodo_pago === '1') {
                    $caja->increment('monto_final', $pago->monto);
                }
    
                $pagos[] = [
                    'pago' => $pago,
                    'credit' => $credit->refresh()
                ];
            }
    
            DB::commit();
    
            return response()->json([
                'pagos' => $pagos
            ]);
    
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'No se pudo completar el registro',
                'message' => $e->getMessage()
            ], 400);
        }
    }
    


}
