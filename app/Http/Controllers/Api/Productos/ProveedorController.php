<?php

namespace App\Http\Controllers\Api\Productos;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proveedor;

class ProveedorController extends Controller
{
    public function index()
    {
        $proveedor = Proveedor::orderBy("nombres", "asc")->paginate(5);
        return response()->json($proveedor);
    }
    public function proveedores()
    {
        $proveedor = Proveedor::orderBy("nombres", "asc")->get();
        return response()->json($proveedor);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
         'tipoidentificacion' => 'required',
            'numidentificacion' => 'required|unique:proveedors',
            'nombres' => 'required',
            'email' => 'unique:proveedors',
        ]);

        $proveedor = Proveedor::create($request->all());
        return response($proveedor, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $proveedor = Proveedor::find($id);
        return response($proveedor);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Proveedor $proveedor)
    {

        $request->validate([
            'tipoidentificacion' => 'required',
            'numidentificacion' => 'required|unique:proveedors,numidentificacion,' . $request->id,
            'nombres' => 'required',
            'email' => 'nullable|email|unique:proveedors,email,' . $request->id,
        ]);
        
        $proveedor = Proveedor::find($request->id);
        $proveedor->tipoidentificacion = $request->tipoidentificacion;
        $proveedor->numidentificacion = $request->numidentificacion;
        $proveedor->nombres = $request->nombres;
        $proveedor->apellidos = $request->apellidos;
        $proveedor->telefono = $request->telefono;
        $proveedor->email = $request->email;
        $proveedor->ubicacion = $request->ubicacion;
        $proveedor->save();

        return response($proveedor, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $proveedor = Proveedor::find($id);
        $proveedor->delete();
    }
}
