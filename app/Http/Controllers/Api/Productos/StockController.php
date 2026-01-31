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
                ->orWhere('codigo_barras', '=', $request->search);
        }
        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }
        if ($request->filled('proveedor_id')) {
            $query->where('proveedor_id', $request->proveedor_id);
        }
                
        if ($request->filled('minimos')) {
            $query->whereColumn('stock', '<', 'stock_minimo');
        }

        // Contar total de productos después de los filtros

        // Aplicar paginación
        $productos = $query->with('categoria', 'proveedor','iva','ibua','ipc')
            ->orderBy('nombre', 'asc')
            ->paginate(50);

        // Agregar el total a la respuesta
        return response()->json($productos);
    }   
}
