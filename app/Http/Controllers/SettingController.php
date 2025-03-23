<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use MercadoPago\Resources\PaymentMethod\Settings;
use Symfony\Component\HttpFoundation\Response;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $negocio = Setting::first();
        return response()->json($negocio);
    }

    /**
     * Show the form for creating a new resource.
     */

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // Validar datos antes de procesar la imagen
    $request->validate([
        'nombre' => 'required',
    ]);

    $logotipo = null;    

    if ($request->hasFile('logotipo')) {
        $file = $request->file('logotipo');           
        $logotipo = $file->getClientOriginalName(); // Agrega timestamp para evitar nombres duplicados
        $file->move(public_path('archivos/images'), $logotipo);
    }

    // Obtener el primer registro o crearlo
    $negocio = Setting::first();

    // Preparar los datos para actualizar o crear
    $data = $request->all();
    if ($logotipo) {
        $data['logotipo'] = $logotipo;
    } elseif ($negocio) {
        $data['logotipo'] = $negocio->logotipo; // Mantener la imagen existente
    }

    if ($negocio) {
        // Si existe, actualiza los datos
        $negocio->update($data);
    } else {
        // Si no existe, crea un nuevo registro
        $negocio = Setting::create($data);
    }

    return response()->json($negocio, Response::HTTP_OK);
}

 

    /**
     * Display the specified resource.
     */
    public function show(Setting $setting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Setting $setting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Setting $setting)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Setting $setting)
    {
        //
    }
}
