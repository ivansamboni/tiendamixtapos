<?php

namespace App\Http\Controllers\Api\Productos;
use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function searchNombreProducto($nombre)
    {
        $productos = Producto::select('id', 'nombre', 'precio', 'stock')
            ->where(function ($query) use ($nombre) {
                $query->where('nombre', 'like', '%' . $nombre . '%');
            })
            ->get();

        return response()->json($productos);
    }

    public function searcCodigoBarrasProducto($codigo)
    {
        $producto = Producto::where('codigo_barras', $codigo)
            ->orWhere('id', $codigo)
            ->firstOrFail();

        return response()->json($producto);
    }

    public function searchCodigoPaginate($codigo)
{
    $productos = Producto::with(['categoria', 'marca', 'proveedor']) // Carga relaciones con Eager Loading
        ->where(function($query) use ($codigo) {
            $query->where('codigo_barras', $codigo)
                  ->orWhere('id', $codigo); // Busca por código de barras o ID
        })
       
        ->select('productos.*') 
        ->paginate(1);

    return response()->json($productos);
}
}