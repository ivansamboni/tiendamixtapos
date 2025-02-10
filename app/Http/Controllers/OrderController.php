<?php

namespace App\Http\Controllers;
use App\Models\Producto;
use App\Models\Order;
use App\Models\Order_detail;
use Illuminate\Http\Request;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;
use Exception;

class OrderController extends Controller
{
    public function searchOrden()
    {
        return view('pages.ordenes');
    }
    public function ordersearch(Request $request)
    {
        $orden = Order::with('detalles.producto')->find($request->id);
        if (!$orden) {
            return redirect()->route('pages.noresults');
        }

        return view('pages.productopagado', compact('orden'));
    }
    public function ordershow($id)
    {
        $producto = Producto::find($id);
        $mpAccessToken = 'APP_USR-983284505153130-020915-df4da19ac41c3a0d983f957debb06301-2259623990';
        if (!$mpAccessToken) {
            throw new Exception("El token de acceso de Mercado Pago no está configurado.");
        }
        MercadoPagoConfig::setAccessToken($mpAccessToken);
        MercadoPagoConfig::setRuntimeEnviroment(MercadoPagoConfig::LOCAL);
        $client = new PreferenceClient();
        $preference = $client->create([

            "items" => [
                [
                    "id" => (string) $producto->id, // Convertimos el ID a string
                    "title" => trim($producto->nombre), // Eliminamos espacios extra                    
                    "quantity" => 1,
                    "unit_price" => (float) $producto->precio // Nos aseguramos de que sea fl
                ],                
            ],
            "statement_descriptor" => "TIENDA ONLINE",
            "external_reference" => "1234567890",
        ]);


        return view('pages.productocomprar', compact('producto', 'preference'));

    }

    public function carritoshow(Request $request)
{
    // Configura el token de acceso de Mercado Pago
    $mpAccessToken = 'APP_USR-983284505153130-020915-df4da19ac41c3a0d983f957debb06301-2259623990';
    if (!$mpAccessToken) {
        throw new Exception("El token de acceso de Mercado Pago no está configurado.");
    }

    // Configura el SDK de Mercado Pago
    MercadoPagoConfig::setAccessToken($mpAccessToken);
    MercadoPagoConfig::setRuntimeEnviroment(MercadoPagoConfig::LOCAL);

    // Valida que se hayan enviado productos
    $request->validate(['productos' => 'required']);

    // Obtén los productos desde la base de datos
    $productos = [];
    foreach ($request->productos as $producto) {
        $productos[] = Producto::find($producto['id']);
    }

    // Construye el array de items para Mercado Pago
    $items = [];
    foreach ($productos as $producto) {
        $items[] = [
            "id" => (string) $producto->id, // Convertimos el ID a string
            "title" => trim($producto->nombre), // Eliminamos espacios extra
            "quantity" => 2,
            "unit_price" => (float) $producto->precio // Nos aseguramos de que sea float
        ];
    }

    // Crea la preferencia de pago
    $client = new PreferenceClient();
    $preference = $client->create([
        "items" => $items, // Pasamos el array de items construido
        "statement_descriptor" => "TIENDA ONLINE",
        "external_reference" => "1234567890",
    ]);

    // Retorna la vista con los productos y la preferencia
    return view('pages.carritocomprar', compact('productos', 'preference'));
}

    public function store(Request $request)
    {
        // Validar los datos recibidos
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
            'comprobante_pago' => $file_name,
            'total' => 0, // Inicialmente 0, se actualizará después
        ]);

        // Crear los detalles de la orden
        $total = 0;
        foreach ($request->productos as $producto) {
            $productoDB = Producto::findOrFail($producto['id']);
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

        return redirect()->route('order.index')->with('orden', $orden->id);

    }


}