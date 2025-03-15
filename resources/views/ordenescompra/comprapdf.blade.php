<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Factura de Venta</title>
    <link rel="stylesheet" href="{{ public_path('bootstrap/css/bootstrap.min.css') }}">

</head>

<body>

    <!-- Encabezado -->
    <div class="row">
        <div class="col-md-6">
            <div class="text-center">
                <h2>{{ $negocio->nombre ?? '' }}</h2>
                <p class="titulo">
                    NIT:{{ $negocio->nit ?? '' }}
                    TEl:{{ $negocio->telefonos ?? '' }}
                    Email:{{ $negocio->email ?? '' }}</p>
            </div>
        </div>
        <br>
        <div class="col-md-6 text-left">
           <h6><small>ORDEN DE COMPRA</h6>
           <h6><small><strong>N°:</strong> {{ $orden->id }}<small></h6>
           <h6><small><strong>Fecha:</strong> {{ $orden->created_at->format('d/m/Y') }}<small></h6>

        </div>
    </div>
    <hr>

    <!-- Datos del Cliente -->
    <div class="row">
        <div class="col-md-6">
            <h6>FACTURAR A:</h6>
            <h6><small><strong>Nombre:</strong> {{ $orden->user->nombres ?? '' }}
                    {{ $orden->user->apellidos ?? '' }}</small></h6>
            <h6><small><strong>CC/NIT:</strong> {{ $orden->user->numidentificacion ?? '' }}</small></h6>
            <h6><small><strong>Tel:</strong> {{ $orden->user->telefono ?? '' }}</small></h6>
            <h6><small><strong>Dirección:</strong> {{ $orden->user->direccion ?? '' }}</small></h6>
        </div>
    </div>
    </div>
    <hr>
    <!-- Tabla de productos -->
    <table class="table table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>Producto</th>
                <th class="text-center">Cantidad</th>
                <th class="text-right">Precio Unitario</th>
                <th class="text-right">Iva</th>
                <th class="text-right">Sub Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orden->purchasedetails as $detalle)
                <tr>
                    <td><small></small>{{ $detalle->producto->nombre }}</small></td>
                    <td class="text-center"><small>{{ $detalle->cantidad }}</small></td>
                    <td class="text-right"><small>${{ number_format($detalle->precio_unitario, 2) }}</small></td>
                    <td class="text-right"><small>${{ number_format($detalle->iva, 2) }}</small></td>
                    <td class="text-right">
                        <small>${{ number_format($detalle->cantidad * $detalle->precio_unitario + $detalle->iva, 2) }}</small>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <hr>

    <!-- Totales -->
    <div class="row">
        <div class="col-md-8"></div>
        <div class="col-md-4">
            <div class="text-end">
                <p><strong>Forma de pago:</strong> {{ $orden->tipo_pago }}</p>
                <h4><strong>Total:</strong> ${{ number_format($orden->total, 2) }}</h4>
            </div>
        </div>
    </div>

</body>

</html>
