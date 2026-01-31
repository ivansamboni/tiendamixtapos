<?php

namespace App\Http\Controllers;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Validation\Rule;

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
        $cliente = Client::withTrashed()->where('numidentificacion', $request->numidentificacion)->first();

        $request->validate([
            'tipoidentificacion' => 'required',
            'numidentificacion' => [
                'required',
                Rule::unique('clients', 'numidentificacion')->ignore($cliente?->id),
            ],
            'nombres' => 'required',
            //'telefono' => 'required',
            //'email' => ['required','email', Rule::unique('clients', 'email')->ignore($cliente?->id),],
        ]);

        if ($cliente) {
            // Restaurar si estaba borrado y actualizar
            $cliente->restore();
            $cliente->update([
                'tipoidentificacion' => $request->tipoidentificacion,
                'nombres' => $request->nombres,
                'apellidos' => $request->apellidos,
                'telefono' => $request->telefono,
                'email' => $request->email,
                'ubicacion' => $request->ubicacion,
            ]);
        } else {
            // Crear nuevo cliente
            $cliente = Client::create([
                'tipoidentificacion' => $request->tipoidentificacion,
                'numidentificacion' => $request->numidentificacion,
                'nombres' => $request->nombres,
                'apellidos' => $request->apellidos,
                'telefono' => $request->telefono,
                'email' => $request->email,
                'ubicacion' => $request->ubicacion,
            ]);
        }
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
        $searchTerm = $request->search;

        // Dividimos por espacios y quitamos vacíos
        $terms = array_filter(explode(' ', $searchTerm));

        $query->where(function ($q) use ($terms) {
            foreach ($terms as $term) {
                $term = "%{$term}%";

                $q->where(function ($sub) use ($term) {
                    $sub->orWhere('nombres', 'LIKE', $term)
                        ->orWhere('apellidos', 'LIKE', $term)
                        ->orWhere('numidentificacion', 'LIKE', $term)
                        ->orWhere('email', 'LIKE', $term);
                });
            }
        });
    }

    $clientes = $query->orderBy('nombres', 'asc')->paginate(20);

    return response()->json($clientes);
}


}
