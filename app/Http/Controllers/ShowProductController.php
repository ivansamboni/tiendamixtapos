<?php

namespace App\Http\Controllers;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Marca;
use Illuminate\Http\Request;

class ShowProductController extends Controller
{

    public function carusel()
    {
        $productos = Producto::with(['categoria', 'marca'])
            ->where('stock', '>', '0')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get(); // Ejecuta la consulta
        $productoall = Producto::with(['categoria', 'marca'])
            ->where('stock', '>', '0')
            ->orderBy('created_at', 'desc')
            ->paginate(24);

        return view('welcome', compact('productos', 'productoall'));
    }

    public function productodetalle($id)
    {

        $producto = Producto::find($id);
        if (!$producto) {
            return redirect()->route('welcome');
        }
        $categoria_id = $producto->categoria_id;
        $productos = Producto::with(['categoria', 'marca'])
            ->where('stock', '>', '0')
            ->where('categoria_id', $categoria_id)
            ->orderBy('created_at', 'desc')
            ->limit(12)
            ->get();

        return view('pages.productodetalle', compact('producto', 'productos'));
    }

    public function categoriatodo($id)
    {
        // Buscar la categoría
        $categoria = Categoria::find($id);

        // Verificar si la categoría existe
        if (!$categoria) {
            return redirect()->route('welcome');
        }

        // Buscar productos de la categoría
        $productos = Producto::where('categoria_id', $id)
            ->where('stock', '>', 0)
            ->paginate(24);

        // Verificar si hay productos
        if ($productos->isEmpty()) {
            return redirect()->route('welcome');
        }

        // Renderizar la vista con los datos
        return view('pages.productoscategorias', compact('productos', 'categoria'));
    }
    public function marcatodo($id)
    {
        // Buscar la categoría
        $marca = Marca::find($id);

        // Verificar si la categoría existe
        if (!$marca) {
            return redirect()->route('welcome');
        }

        // Buscar productos de la categoría
        $productos = Producto::where('marca_id', $id)
            ->where('stock', '>', 0)
            ->paginate(24);

        // Verificar si hay productos
        if ($productos->isEmpty()) {
            return redirect()->route('welcome');
        }

        // Renderizar la vista con los datos
        return view('pages.productosmarcas', compact('productos', 'marca'));
    }

    public function searchNombreProducto(Request $request)
    {
        $nombre = trim($request->input('nombre'));  // Elimina espacios en los extremos
        $palabras = explode(' ', $nombre);  // Divide en palabras individuales

        // Verifica que haya al menos una palabra con más de 2 caracteres
        $palabrasValidas = array_filter($palabras, fn($p) => strlen($p) >= 4);

        if (!empty($palabrasValidas)) {
            // Realiza la búsqueda con LIKE para cada palabra válida
            $productosQuery = Producto::where(function ($query) use ($palabrasValidas) {
                foreach ($palabrasValidas as $palabra) {
                    $query->orWhere('nombre', 'LIKE', '%' . $palabra . '%')
                          ->where('stock', '>', 0);
                }
            })->orWhereHas('categoria', function ($query) use ($palabrasValidas) {
                foreach ($palabrasValidas as $palabra) {
                    $query->where('nombre', 'LIKE', '%' . $palabra . '%');
                }
            })->orderBy('nombre', 'desc');
            $totalresult = $productosQuery->count();
            $productos = $productosQuery->paginate(20)->appends(request()->query());
        } else {
            return view('pages.noresults');
        }

        return view('pages.productoresult', compact('productos', 'totalresult'));
    }


}


