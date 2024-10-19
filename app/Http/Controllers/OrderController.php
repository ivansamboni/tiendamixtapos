<?php

namespace App\Http\Controllers;
use App\Models\Producto;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function ordershow($id)
    {
        $producto = Producto::find($id);
        return view('pages.productocomprar', compact('producto'));

    }

    public function store(Request $request)
    {
               
        $id = $request->input('id');

        $producto = Producto::findOrFail($id); 

        if ($request->hasFile('comprobante_pago')) {
            $comprobante_pago = $request->file('comprobante_pago');
            $timestamp = now()->format('His');
            $file_name = $request->cedula. $timestamp . $comprobante_pago->getClientOriginalName();
            $comprobante_pago->move(public_path('archivos\comprobantes_pagos'), $file_name);
        }
        
        $orden = new Order();
        $orden->nombres = $request->input('nombres');
        $orden->apellidos = $request->input('apellidos');
        $orden->cedula = $request->input('cedula');
        $orden->email = $request->input('email');
        $orden->telefono = $request->input('telefono');
        $orden->departamento = $request->input('departamento');
        $orden->ciudad = $request->input('ciudad');
        $orden->direccion = $request->input('direccion');
        $orden->comprobante_pago = $file_name;
        $orden->total = $request->input('total');
       // $orden->estado = $request->input('estado'); 

        $orden->save();           
        return view('pages.productopagado', compact('orden'));   
    }
}
