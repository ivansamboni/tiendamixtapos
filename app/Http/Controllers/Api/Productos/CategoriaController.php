<?php

namespace App\Http\Controllers\Api\Productos;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categoria = Categoria::orderBy("nombre", "asc")->paginate(5);
        return response()->json($categoria);
    }
    public function categorias()
    {
        $categoria = Categoria::orderBy("nombre", "asc")->get();
        return response()->json($categoria);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:categorias',
        ]);

        $categoria = Categoria::create($request->all());
        return response($categoria, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $categoria = Categoria::find($id);
        return response($categoria);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Categoria $categoria)
    {

        $request->validate([
            'nombre' => 'required',
        ]);

        $categoria->fill($request->all())->save();

        return response($categoria, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $categoria = Categoria::find($id);
        $categoria->delete();
    }
}
