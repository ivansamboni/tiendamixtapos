<?php

namespace App\Http\Controllers\Api\Productos;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Seller;

class SellerController extends Controller
{
    public function index()
    {
        $proveedor = Seller::orderBy("nombres", "asc")->paginate(20);
        return response()->json($proveedor);
    }
    public function proveedores()
    {
        $proveedor = Seller::orderBy("nombres", "asc")->get();

        
        return response()->json($proveedor);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tipoidentificacion' => 'required',
            'numidentificacion' => 'required|unique:clients,numidentificacion',
            'nombres' => 'required',
            'email' => 'nullable|unique:clients,email,NULL,id,deleted_at,NULL',
        ]);

        $proveedor = Seller::create($request->all());
        return response($proveedor, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $proveedor = Seller::find($id);
        return response($proveedor);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Seller $proveedor)
    {

        $request->validate([
            'tipoidentificacion' => 'required',
            'numidentificacion' => 'required|unique:sellers,numidentificacion,' . $request->id,
            'nombres' => 'required',
            'email' => 'nullable|email|unique:sellers,email,' . $request->id,
        ]);
        
        $proveedor = Seller::find($request->id);
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
        $proveedor = Seller::find($id);
        $proveedor->delete();
    }

    public function proveedorSearch(Request $request)
    {
        $query = Seller::query();

        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where('nombres', 'LIKE', $searchTerm)
                ->orWhere('apellidos', 'LIKE', $searchTerm)
                ->orWhere('numidentificacion', 'LIKE', $searchTerm);
        }

        // Guardamos la paginación en una variable antes de retornarla
        $seller = $query->orderBy('nombres', 'asc')->paginate(20);

        return response()->json($seller);
    }
}
