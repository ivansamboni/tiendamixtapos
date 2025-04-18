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
    th, td {
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
            <h4 class="blue-header">FACTURA</h4>
            <p><strong>N°:</strong> {{ $orden->factura_numero }}</p>
            <p><strong>Fecha:</strong> {{ $orden->created_at->format('d/m/Y H:i') }}</p>
            <p><strong>Tipo:</strong> {{ $orden->status }}</p>
            <p><strong>Creado por:</strong> {{ $orden->user->nombres }}</p>
            <p><strong>Descripción:</strong> {{ $orden->descripcion }}</p>

        </div>
    </div>
    <hr>   
    <!-- Tabla de productos -->
    <table class="table table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>Producto</th>
                <th class="text-center">Stock nuevo</th>
                <th class="text-right">Cantidad ajustada</th>               
            </tr>
        </thead>
        <tbody>
            @foreach ($orden->ajustedetails as $detalle)
                <tr>
                    <td>{{ $detalle->producto->nombre }}</td>
                    <td>{{ $detalle->producto->stock }}</td>
                    <td class="text-center">{{ sprintf('%+d', $detalle->stock_cambio) }}</td>                   
                </tr>
            @endforeach
        </tbody>
    </table>    
    <hr>

</body>

</html>
