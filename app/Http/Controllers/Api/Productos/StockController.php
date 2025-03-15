<?php

namespace App\Http\Controllers\Api\Productos;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producto;
class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Producto::query();

        // Filtros dinámicos
        if ($request->filled('search')) {
            $query->where('nombre', 'LIKE', '%' . $request->search . '%')
                ->orWhere('codigo_barras', 'LIKE', '%' . $request->search . '%');
        }
        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }
        if ($request->filled('proveedor_id')) {
            $query->where('proveedor_id', $request->proveedor_id);
        }
        if ($request->filled('stock_min')) {
            $query->where('stock', '>=', $request->stock_min);
        }
        if ($request->filled('stock_max')) {
            $query->where('stock', '<=', $request->stock_max);
        }
        if ($request->filled('minimos')) {
            $query->whereColumn('stock', '<=', 'stock_minimo');
        }

        // Contar total de productos después de los filtros

        // Aplicar paginación
        $productos = $query->with('categoria', 'proveedor')
            ->orderBy('nombre', 'asc')
            ->paginate(50);

        // Agregar el total a la respuesta
        return response()->json($productos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
