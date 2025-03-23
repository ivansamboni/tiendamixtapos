<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Factura de Venta</title>
    <link rel="stylesheet" href="{{ public_path('bootstrap/css/bootstrap.min.css') }}">

</head>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        border: 1px solid #ddd;
    }

    th,
    td {
        padding: 8px;
        border: 1px solid #ddd;
    }

    th {
        background-color: #f4f4f4;
    }

    .text-left {
        text-align: left;
    }

    .text-right {
        text-align: right;
    }
</style>

<body>
    <header>
        <!-- Encabezado -->
        <div class="row">
            <div class="col-md-6">
                <div class="text-center">
                    <img src="{{ public_path('archivos/images/' . ($negocio->logotipo ?? 'default.png')) }}"
                        width="60" alt="Logotipo">
                    <br>
                    <h4>{{ $negocio->nombre ?? '' }}</h4>
                    <small class="titulo">
                        NIT:{{ $negocio->nit ?? '' }}
                        TEl:{{ $negocio->telefonos ?? '' }}
                    </small>
                </div>
            </div>
            <br>
            <div class="col-md-6 text-left">
                <h4 class="blue-header">FACTURA</h4>
                <p><strong>N°:</strong> {{ $orden->id }}</p>
                <p><strong>Fecha:</strong> {{ $orden->created_at->format('d/m/Y H:i') }}</p>

            </div>
        </div>
    </header>
    <hr>

    <!-- Datos del Cliente -->
    <div class="row">
        <div class="col-md-6">

            <h5>FACTURAR A:</h5>
            <p><strong>Nombre:</strong> {{ $orden->client->nombres }} {{ $orden->client->apellidos }}</p>
            <p><strong>CC/NIT:</strong> {{ $orden->client->numidentificacion }}</p>
            <p><strong>Tel:</strong> {{ $orden->client->telefono }}</p>
            <p><strong>Dirección:</strong> {{ $orden->client->ubicacion }}</p>
        </div>
    </div>
    </div>
    <hr>
    <!-- Tabla de productos -->
    <table class="table table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>Producto</th>
                <th class="text-center">CANT</th>
                <th class="text-right">P.UNI</th>
                <th class="text-right">Iva</th>
                <th class="text-right">Sub Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orden->details as $detalle)
                <tr>
                    <td>{{ $detalle->producto->nombre }}</td>
                    <td class="text-center">{{ $detalle->cantidad }}</td>
                    <td class="text-right">${{ number_format($detalle->precio_unitario, 2) }}</td>
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
            <div class="total-box">
                <h4><strong>Total:</strong> ${{ number_format($orden->total, 2) }}</h4>
                <p><strong>Forma de pago:</strong> {{ $orden->tipo_pago }}</p>
            </div>
        </div>
    </div>

</body>

</html>
