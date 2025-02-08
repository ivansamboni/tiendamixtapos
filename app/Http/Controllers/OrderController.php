<?php

namespace App\Http\Controllers;
use App\Models\Producto;
use App\Models\Order;
use App\Models\Order_detail;
use Illuminate\Http\Request;

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
        return view('pages.productocomprar', compact('producto'));

    }

    public function carritoshow(Request $request)
    {

        $request->validate(['productos' => 'required']);
        foreach ($request->productos as $producto) {
            $productos[] = Producto::find($producto['id']);
        }

        return view('pages.carritocomprar', compact('productos')); // Retornar la vista con el producto
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
            'comprobante_pago' => 'required|file',
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