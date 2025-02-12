<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Order;
use App\Models\Order_detail;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;
use Exception;




class MercadoPagoController extends Controller
{

    public function index()
    {

        return view('pages.checkout');

    }

    public function createPaymentPreference(Request $request)
    {
        $mpAccessToken = 'APP_USR-983284505153130-020915-df4da19ac41c3a0d983f957debb06301-2259623990';
        if (!$mpAccessToken) {
            throw new Exception("El token de acceso de Mercado Pago no está configurado.");
        }

        // Configura el SDK de Mercado Pago
        MercadoPagoConfig::setAccessToken($mpAccessToken);
        MercadoPagoConfig::setRuntimeEnviroment(MercadoPagoConfig::LOCAL);

        // Valida que se hayan enviado productos
        $request->validate([
            'nombres' => 'required',
            'apellidos' => 'required',
            'cedula' => 'required',
            'email' => 'required|email',
            'telefono' => 'required',
            'departamento' => 'required',
            'ciudad' => 'required',
            'direccion' => 'required',

        ]);

        // Procesar el comprobante de pago
        if ($request->hasFile('comprobante_pago')) {
            $comprobante_pago = $request->file('comprobante_pago');
            $timestamp = now()->format('His');
            $file_name = $request->cedula . $timestamp . $comprobante_pago->getClientOriginalName();
            $comprobante_pago->move(public_path('archivos/comprobantes_pagos'), $file_name);
        }

        // Crear la orden
        $orden = Order::create([
            'nombres' => $request->nombres,
            'apellidos' => $request->apellidos,
            'cedula' => $request->cedula,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'departamento' => $request->departamento,
            'ciudad' => $request->ciudad,
            'direccion' => $request->direccion,
            'comprobante_pago' => $file_name ?? 'Nul',
            'total' => 0, // Inicialmente 0, se actualizará después
        ]);

        // Crear los detalles de la orden
        $total = 0;
        $items = [];
        foreach ($request->productos as $producto) {
            $productoDB = Producto::findOrFail($producto['id']);
            $items[] = [
                "id" => (string) $productoDB->id, // Convertimos el ID a string
                "title" => trim($productoDB->nombre), // Eliminamos espacios extra
                "quantity" => (float) $producto['cantidad'],
                "unit_price" => (float) $productoDB->precio // Nos aseguramos de que sea float
            ];
            $precioUnitario = $productoDB->precio;
            $stock = $productoDB->stock;
            $cantidad = $producto['cantidad'];
            $stockActual = $stock - $cantidad;
            $productoDB->update(['stock' => $stockActual]);
            // Guardar el detalle de la orden
            $orden_detalle = Order_detail::create([
                'order_id' => $orden->id,
                'producto_id' => $productoDB->id,
                'cantidad' => $cantidad,
                'precio_unitario' => $precioUnitario,
            ]);

            // Calcular el total acumulado
            $total += $precioUnitario * $cantidad;
        }
        // Actualizar el total en la orden
        $orden->update(['total' => $total]);

        $client = new PreferenceClient();
        $preference = $client->create([
            "items" => $items, // Pasamos el array de items construido
            "payer" => [
                "name" => $request->nombres,
                "surname" => $request->apellidos,
                "email" => $request->email,
                "phone" => [
                    "area_code" => "57",
                    "number" => $request->telefono,
                ],
                "identification" => [
                    "type" => "CC",
                    "number" => $request->cedula,
                ],
                "address" => [

                    "street_number" => $request->direccion,
                ]

            ],
            "statement_descriptor" => "TIENDA ONLINE",
            "external_reference" => "1234567890",

        ]);

        $requestData = $request->all();
        $orderId = $orden->id;

        session()->put('preference', $preference->id);
        session()->put('requestData', $requestData);
        session()->put('total', $total);
        session()->put('orderId', $orderId);

        return redirect()->route('checkout.order');
        
        //return view('pages.checkout', compact('preference','requestData','total','orderId'));
    }

}