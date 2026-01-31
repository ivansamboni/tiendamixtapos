<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use Illuminate\Http\Request;

class CajaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Caja::with('user');
    
        if ($request->filled('search')) {
            $terms = array_filter(explode(' ', $request->search));
    
            $query->where(function ($q) use ($terms) {
                foreach ($terms as $term) {
                    $term = "%{$term}%";
    
                    $q->where(function ($sub) use ($term) {
                        $sub->orWhere('id', 'like', $term)         
                            ->orWhere('apertura', 'like', $term)                               
                            ->orWhereHas('user', function ($q2) use ($term) {
                                $q2->where('nombres', 'like', $term)
                                ->orWhere('apellidos', 'like', $term)
                                ->orWhere('numidentificacion', 'like', $term)
                                   ->orWhere('email', 'like', $term);
                            });
                    });
                }
            });
        }
    
        $cajas = $query->orderBy('apertura', 'desc')->paginate(20);
    
        return response()->json($cajas);
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validación de datos
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'caja_numero' => 'required|string|max:255',
            'monto_inicial' => 'required|numeric|min:0',
            'observaciones' => 'nullable|string|max:1000',
        ]);

        // Verificar si ya hay una caja abierta para ese usuario (opcional)
        $cajaAbierta = Caja::where('user_id', $validated['user_id'])
            ->whereNull('cierre')
            ->first();

        if ($cajaAbierta) {
            return response()->json([
                'message' => 'Ya hay una caja abierta para este usuario.'
            ], 422);
        }

        // Crear la caja
        $caja = Caja::create([
            'user_id' => $validated['user_id'],
            'caja_numero' => $validated['caja_numero'],
            'monto_inicial' => $validated['monto_inicial'],
            'monto_final' => $validated['monto_inicial'],
            'apertura' => now(),
            'observaciones' => $validated['observaciones'] ?? null,
        ]);

        return response()->json([
            'message' => 'Caja abierta correctamente.',
            'caja' => $caja
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
{
    $caja = Caja::with(['user', 'movimientos'])

        // 🔹 Ventas (solo referencia, NO caja)
        ->withSum([
            'sales as total_ventas_contado' => function ($query) {
                $query->where('metodo_pago', '10'); // contado
            }
        ], 'total')

        ->withSum([
            'sales as total_ventas_transferencias' => function ($query) {
                $query->where('metodo_pago', '47'); // transferencia
            }
        ], 'total')

        ->withSum([
            'sales as total_ventas_tarjetas' => function ($query) {
                $query->whereIn('metodo_pago',['48', '49']); // tarjeta
            }
        ], 'total')

        ->withSum([
            'sales as total_ventas_credito' => function ($query) {
                $query->where('forma_pago', '2'); // crédito
            }
        ], 'total')

        // 🔹 Dinero REAL en caja (payments)
        ->withSum([
            'payments as total_pagos_efectivo' => function ($query) {
                $query->where('metodo_pago', '10'); // efectivo
            }
        ], 'monto')

        ->withSum([
            'payments as total_pagos_tarjetas' => function ($query) {
                $query->whereIn('metodo_pago', ['48', '49']); // crédito / débito
            }
        ], 'monto')

        ->withSum([
            'payments as total_pagos_transferencias' => function ($query) {
                $query->where('metodo_pago', '47'); // transferencia
            }
        ], 'monto')

        // 🔹 Movimientos manuales
        ->withSum([
            'movimientos as ingresos' => function ($query) {
                $query->where('tipo', 'ingreso');
            }
        ], 'monto')

        ->withSum([
            'movimientos as salidas' => function ($query) {
                $query->where('tipo', 'salida');
            }
        ], 'monto')

        ->findOrFail($id);

    return response()->json($caja);
}

    public function cajaSearch(Request $request)
    {
        $query = Caja::query();

        if ($request->filled('id')) {
            $query->where('id', $request->id);
        }

        if ($request->filled('fecha')) {
            $searchTerm = '%' . $request->fecha . '%';
            $query->where('apertura', 'LIKE', $searchTerm);

        }

        // Guardamos la paginación en una variable antes de retornarla
        $cajas = $query->with('user')->orderBy('apertura', 'desc')->paginate(20);

        return response()->json($cajas);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $caja = Caja::findOrFail($id);
        if ($caja->cierre !== null) {
            return response()->json(['message' => 'La caja ya fue cerrada y no puede modificarse.'], 422);
        }
        // Validar los datos que se van a actualizar
        $request->validate([            
            'observaciones' => 'nullable|string',
        ]);

        //$caja->monto_final = $request->monto_final;
        $caja->cierre = now();
        $caja->observaciones = $request->observaciones ?? null;

        $caja->save();

        return response()->json([
            'message' => 'Caja cerrada con éxito.',
            'caja' => $caja
        ]);
    }

    public function validarAperturaCaja($cajero_id)
    {
        $cajaAbierta = Caja::where('user_id', $cajero_id)
            ->whereNull('cierre')
            ->first();
    
        if ($cajaAbierta) {
            return response()->json([
                'status' => 'success',
                'message' => 'Caja abierta.'
            ], 200);
        }
    
        return response()->json([
            'status' => 'error',
            'message' => 'No has hecho apertura de caja.'
        ], 200);
    }
    

}
