@extends('layouts.layout')
@section('title', 'Página de Inicio')
@section('content')
    <br><br>
    <h3 class="text-center">Tu orden</h3>

    <div class="container mt-3">
        <div class="row">
            <div class="col-md-6">
                <h5>Cliente</h5>
                <p>Nombre: {{ $orden->nombres }}, {{ $orden->apellidos }}</p>
                <p>Teléfono: {{ $orden->telefono }}</p>
                <p>Dirección: {{ $orden->direccion }}</p>
                <p>Ciudad: {{ $orden->ciudad }}, ({{ $orden->departamento }})</p>
            </div>
            <div class="col-md-6 text-right">
                <h4 class="mb-0">#Orden, {{ $orden->id }}</h4>
                <p>Fecha: <strong>{{ $orden->created_at->format('d/m/Y H:i') }}</strong></p>
                <p>Estado: <strong>{{ $orden->estado }}</strong></p>
            </div>
        </div>

        <hr>

        <!-- Tabla de productos/servicios -->
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orden->detalles as $detalle)
                    <tr>
                        <td>{{ $detalle->producto->nombre }}</td>
                        <td>{{ $detalle->cantidad }}</td>
                        <td>{{ number_format($detalle->precio_unitario) }}</td>
                        <td>{{ number_format($detalle->cantidad * $detalle->precio_unitario) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" class="text-right">Total</th>
                    <th>${{ number_format($orden->total) }}</th>
                </tr>
            </tfoot>
        </table>

        <hr>

        <!-- Información adicional -->
        <div class="row">
            <div class="col-md-12">
                <p class="text-center">Gracias por su compra. Por favor, conserve esta factura como comprobante.</p>
            </div>
        </div>
    </div>


@endsection
