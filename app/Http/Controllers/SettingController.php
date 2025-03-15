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
    public function create()
    {
        $negocio = Setting::orderBy("nombres", "asc")->paginate(5);
        return response()->json($negocio);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       
        $request->validate([
            'nombre' => 'required',
        ]);
    
        // Obtiene el primer registro o crea uno si no existe
        $negocio = Setting::first();
    
        if ($negocio) {
            // Si existe, actualiza los datos
            $negocio->update($request->all());
        } else {
            // Si no existe, crea un nuevo registro
            $negocio = Setting::create($request->all());
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
