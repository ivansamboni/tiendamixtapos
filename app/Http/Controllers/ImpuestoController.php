<?php

namespace App\Http\Controllers;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Impuesto;
use Illuminate\Http\Request;

class ImpuestoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $impuesto = Impuesto::orderBy("nombre", "asc")->get();
        return response()->json($impuesto);
    }
       /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:impuestos',
            'valor' => 'required',
        ]);

        $impuesto = Impuesto::create($request->all());
        return response($impuesto, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $impuesto = Impuesto::find($id);
        return response($impuesto);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Impuesto $impuesto)
    {

        $request->validate([
            'nombre' => 'required',
            'valor' => 'required',
        ]);

        $impuesto->fill($request->all())->save();

        return response($impuesto, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $impuesto = Impuesto::find($id);
        $impuesto->delete();
    }
}
