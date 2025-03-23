<?php

namespace App\Http\Controllers;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;

class ClientController extends Controller
{
    public function index()
    {
        $cliente = Client::orderBy("nombres", "asc")->paginate(20);
        return response()->json($cliente);
    }
    public function clienteFinalDatos(string $cedula)
    {
        $cliente = Client::where('numidentificacion', $cedula)->first(); // Usar first() para obtener un solo resultado

        if (!$cliente) {
            return response()->json(['message' => 'Cliente no encontrado'], 404);
        }

        return response()->json($cliente);
    }
    public function clients()
    {
        $cliente = Client::orderBy("nombres", "asc")->get();


        return response()->json($cliente);
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

        $cliente = Client::create($request->all());
        return response($cliente, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $cliente = Client::find($id);
        return response($cliente);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $cliente)
    {

        $request->validate([
            'tipoidentificacion' => 'required',
            'numidentificacion' => 'required|unique:clients,numidentificacion,' . $request->id,
            'nombres' => 'required',
            'email' => 'nullable|email|unique:clients,email,' . $request->id,
        ]);

        $cliente = Client::find($request->id);
        $cliente->tipoidentificacion = $request->tipoidentificacion;
        $cliente->numidentificacion = $request->numidentificacion;
        $cliente->nombres = $request->nombres;
        $cliente->apellidos = $request->apellidos;
        $cliente->telefono = $request->telefono;
        $cliente->email = $request->email;
        $cliente->ubicacion = $request->ubicacion;
        $cliente->save();

        return response($cliente, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cliente = Client::find($id);
        $cliente->delete();
    }

    public function clienteSearch(Request $request)
    {
        $query = Client::query();

        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where('nombres', 'LIKE', $searchTerm)
                ->orWhere('apellidos', 'LIKE', $searchTerm)
                ->orWhere('numidentificacion', 'LIKE', $searchTerm);
        }

        // Guardamos la paginación en una variable antes de retornarla
        $clientes = $query->orderBy('nombres', 'asc')->paginate(20);

        return response()->json($clientes);
    }
}
