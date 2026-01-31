<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use App\Models\CajaMovimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CajaMovimientoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json('sos pelotudo');
    }

    /**
     * Store a newly created resource in storage.
     */

    
    public function store(Request $request)
    {
        // 1. Validar datos
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'caja_id' => 'required|exists:cajas,id',
            'tipo'    => 'required|in:ingreso,salida',
            'monto'   => 'required|numeric|min:0.01', // si COP sin centavos: integer|min:1
            'descripcion'   => 'nullable|string|max:255',
            'referencia'    => 'nullable|string|max:255',
            'observaciones' => 'nullable|string|max:1000',
        ]);
    
        // 2. Verificar caja abierta (sin bloquear)
        $cajaAbierta = Caja::where('id', $validated['caja_id'])
            ->where('user_id', $validated['user_id'])
            ->whereNull('cierre')
            ->first();
    
        if (!$cajaAbierta) {
            return response()->json([
                'message' => 'No existe una caja abierta para este usuario.'
            ], 422);
        }
    
        $movimiento = null;
    
        // 3. Actualizar saldo en transacción con bloqueo
        DB::transaction(function () use ($validated, &$movimiento, $cajaAbierta) {
            // Bloquear la fila de la caja
            $caja = Caja::whereKey($cajaAbierta->id)->lockForUpdate()->first();
            $monto = (float) $validated['monto'];
    
            if ($validated['tipo'] === 'salida') {
                // Evitar saldo negativo
                if ($caja->monto_final < $monto) {
                    throw ValidationException::withMessages([
                        'monto' => 'El egreso no puede dejar la caja con saldo negativo.'
                    ]);
                }
                $caja->decrement('monto_final', $monto); // <- uso correcto
            } else {
                $caja->increment('monto_final', $monto); // <- uso correcto
            }
    
            // 4. Registrar el movimiento
            $movimiento = CajaMovimiento::create($validated);
        });
    
        // 5. Respuesta
        return response()->json([
            'message' => 'Movimiento registrado correctamente',
            'data' => $movimiento
        ], 201);
    }
    
    



    /**
     * Display the specified resource.
     */
    public function show(CajaMovimiento $cajaMovimiento)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CajaMovimiento $cajaMovimiento)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CajaMovimiento $cajaMovimiento)
    {
        //
    }
}
