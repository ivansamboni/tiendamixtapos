<?php

namespace App\Http\Controllers\Api\Productos;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Marca;

class MarcaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $marca = Marca::orderBy("nombre", "asc")->paginate(20);
        return response()->json($marca);
    }
    public function marcas()
    {
        $marca = Marca::orderBy("nombre", "asc")->get();
        return response()->json($marca);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:marcas',
        ]);

        $marca = Marca::create($request->all());
        return response($marca, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $marca = Marca::find($id);
        return response($marca);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Marca $marca)
    {

        $request->validate([
            'nombre' => 'required',
        ]);

        $marca->fill($request->all())->save();

        return response($marca, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $marca = Marca::find($id);
        $marca->delete();
    }
}
