<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Ticket de Venta</title>
    <style>
        body {
            font-family: monospace;
            /* Usar fuente tipo recibo */
            font-size: 10px;
            width: 58mm;
            /* Ancho típico de impresora térmica */
        }
    
        .ticket {
            text-align: center;
        }
    
        .titulo {
            font-size: 14px;
            font-weight: bold;
        }
    
        .productos {
            text-align: left;
            width: 100%;
            border-collapse: collapse;
        }
    
        .productos th,
        .productos td {
            padding: 4px 6px; /* Más espacio entre el contenido y los bordes */            
        }
    
        .productos th {
            text-align: left;
            font-weight: bold;
            background-color: #f0f0f0; /* Fondo suave para destacar encabezados */
        }
    
        .productos td {
            vertical-align: middle; /* Alinear verticalmente */
        }
    
        .total {
            font-weight: bold;
            font-size: 12px;
        }
    
        .gracias {
            margin-top: 12px;
            font-size: 10px;
        }
    </style>
</head>

<body>
    <div class="ticket">
        <h2>{{ $negocio->nombre ?? '' }}</h2>
        <p class="titulo">
            NIT:{{ $negocio->nit ?? '' }}
            TEl:{{ $negocio->telefonos ?? '' }}
            Email:{{ $negocio->email ?? '' }}</p>
        <p>Factura de venta #: {{ $orden->id }}</p>
        <p>Fecha:{{ $orden->created_at->format('d/m/Y H:i') }}</p>
        <p>Vendedor: {{ $orden->user->nombres ?? '' }}, {{ $orden->user->apellidos ?? '' }} </p>
        <p>CC: {{ $orden->user->numidentificacion ?? '' }}</p>
        <p>Cliente: {{ $orden->client->nombres ?? '' }}, {{ $orden->client->apellidos ?? '' }}</p>
        <p>CC/NIT: {{ $orden->client->numidentificacion ?? '' }}</p>

        <table class="productos">
            <thead>
                <tr>
                    <th>PRODUCTO</th>
                    <th>CANT.</th>
                    <th>P.UNI</th>  
                    <th>IVA</th>                  
                    <th>MONTO</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalCantidad = 0;
                @endphp
                @foreach ($orden->details as $detalle)
                    @php
                        $totalCantidad += $detalle->cantidad;
                    @endphp
                    <tr>
                        <td>{{ $detalle->producto->nombre }}</td>
                        <td>{{ $detalle->cantidad }}</td>
                        <td>{{ number_format($detalle->precio_unitario) }} </td>  
                        <td class="text-right"><small>{{ number_format($detalle->iva) }}</small></td>                      
                        <td> <small>{{ number_format($detalle->cantidad * $detalle->precio_unitario + $detalle->iva, 2) }}</small>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <hr>         

        <p>Forma de pago: {{ $orden->tipo_pago }}</p>
        <p class="total">Total: ${{ number_format($orden->total) }}</p>
        <hr>
        <p class="gracias">¡Gracias por su compra!</p>
    </div>
</body>
<script>
    window.onload = function() {
        window.print();
    };
</script>

</html>
