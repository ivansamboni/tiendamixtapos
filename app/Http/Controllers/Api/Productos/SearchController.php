<?php

namespace App\Http\Controllers\Api\Productos;
use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function searchNombreProducto($nombre)
    {

        $nombre = trim($nombre); // Elimina espacios en los extremos
        $palabras = explode(' ', $nombre);

        $palabrasValidas = array_filter($palabras, fn($p) => strlen($p) >= 3);

        if (empty($palabrasValidas)) {
            return response()->json([], 200); // Si no hay palabras válidas, retorna vacío
        }

        $productos = Producto::where(function ($query) use ($palabrasValidas) {
                foreach ($palabrasValidas as $palabra) {
                    $query->orWhere('nombre', 'LIKE', '%' . $palabra . '%');
                }
            })->get();

        return response()->json($productos);
    }

    public function searcCodigoBarrasProducto($codigo)
{
    $producto = Producto::where('codigo_barras', $codigo)->first();

    if (!$producto) {
        return response()->json(['error' => 'Producto no encontrado'], 404);
    }

    return response()->json($producto);
}


    public function searchCodigoPaginate($codigo)
    {
        $productos = Producto::with(['categoria', 'marca', 'proveedor']) // Carga relaciones con Eager Loading
            ->where(function ($query) use ($codigo) {
                $query->where('codigo_barras', $codigo)
                    ->orWhere('id', $codigo); // Busca por código de barras o ID
            })

            ->select('productos.*')
            ->paginate(1);

        return response()->json($productos);
    }
}