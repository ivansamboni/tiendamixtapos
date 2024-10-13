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
            ->orderBy('created_at', 'desc')
            ->limit(12)
            ->get(); // Ejecuta la consulta
        $productoall = Producto::with(['categoria', 'marca'])
            ->orderBy('created_at', 'desc')           
            ->paginate(24);
        $categoria = Categoria::orderBy("nombre", "asc")->get();
        $marca = Marca::orderBy("nombre", "asc")->get();

        return view('welcome', compact('productos', 'categoria', 'marca','productoall'));
    }

    public function categorias()
    {
        $categoria = Categoria::orderBy("nombre", "asc")->get();

        return view('layouts.layout', compact('categoria'));
    }

}


