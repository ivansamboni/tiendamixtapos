<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Factura de Venta</title>
    <link rel="stylesheet" href="{{ public_path('bootstrap/css/bootstrap.min.css') }}">

    <style>
    
        header {                        
            left: 0;
            right: 0;
            height: 80px;
            text-align: center;
            line-height: 20px;
        }

        .content {
            margin-top: 60px;
        }
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

.v-progress-circular {
    margin: 1rem;
}
    </style>
</head>

<body>

    <!-- Encabezado fijo -->
    <header>
        <div class="row">
            <div class="col-md-6 text-center">
                <img src="{{ public_path('archivos/images/' . ($negocio->logotipo ?? 'default.png')) }}" width="60" alt="Logotipo">
                <h4>{{ $negocio->nombre ?? '' }}</h4>
                <small>
                    NIT: {{ $negocio->nit ?? '' }} | Tel: {{ $negocio->telefonos ?? '' }}
                </small>
            </div>            
        </div>
    </header>

    <!-- Contenido principal -->
    <div class="content">
        <!-- Datos del Cliente -->
        <div class="row">
            <div class="col-md-6">
                <h6><strong>FACTURA N°:</strong> {{ $orden->factura_numero }}</h6>
                <h6><strong>Fecha:</strong> {{ $orden->created_at->format('d/m/Y H:i') }}</h6>
                <h6><strong>Proveedor:</strong> {{ $orden->seller->nombres ?? '' }} {{ $orden->seller->apellidos ?? '' }}</h6>
                <h6><strong>CC/NIT:</strong> {{ $orden->seller->numidentificacion ?? '' }}</h6>
                <h6><strong>Tel:</strong> {{ $orden->seller->telefono ?? '' }}</h6>
            </div>
        </div>

        <hr>

        <!-- Tabla de productos -->
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Producto</th>
                    <th class="text-center">Cantidad</th>
                    <th class="text-right">Precio Compra</th>
                    <th class="text-right">IVA</th>
                    <th class="text-right">IBUA</th>
                    <th class="text-right">IPC</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orden->purchasedetails as $detalle)
                    <tr>
                        <td>{{ $detalle->producto->nombre }}</td>
                        <td class="text-center">{{ $detalle->cantidad }}</td>
                        <td class="text-right">${{ number_format($detalle->precio_unitario, 2) }}</td>
                        <td class="text-right">${{ number_format($detalle->iva, 2) }}</td>
                        <td class="text-right">${{ number_format($detalle->ibua, 2) }}</td>
                        <td class="text-right">${{ number_format($detalle->ipc, 2) }}</td>
                        <td class="text-right">${{ number_format($detalle->cantidad * $detalle->precio_unitario + $detalle->iva +$detalle->ibua + $detalle->ipc, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <!-- Totales -->
        <div class="row">
            <div class="col-md-8"></div>
            <div class="col-md-4 text-left">
                <p><strong>Forma de pago:</strong> {{ $orden->tipo_pago }} <strong>Total:</strong> ${{ number_format($orden->total, 2) }}</p>
                
            </div>
        </div>
    </div>

</body>

</html>
