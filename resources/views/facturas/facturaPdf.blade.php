<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Recibo de Venta</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 20px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 5px;
        }

        .no-border th,
        .no-border td {
            border: none;
        }

        h2,
        h3 {
            margin: 5px 0;
        }

        .highlight {
            font-weight: bold;
            font-size: 12px;
        }

        .observaciones {
            margin-top: 20px;
            font-size: 10px;
        }
    </style>
</head>

<body>

    <!-- Encabezado -->
    <table class="no-border">
        <tr>
            <td style="width: 30%;">
                <img src="{{ public_path('archivos/images/' . ($negocio->logotipo ?? 'default.png')) }}" width="90"
                    alt="Logotipo">
            </td>
            <td class="text-center">
                <h2>{{ $negocio->nombre ?? 'Nombre de Empresa' }}</h2>

                <span>NIT: {{ $negocio->nit ?? '' }}</span><br>
                <span>{{ $negocio->direccion ?? '' }}</span><br>
                <span>Tel: {{ $negocio->telefonos ?? '' }}</span><br>
                <span>{{ $negocio->email ?? '' }}</span><br>

            </td>
            <td class="text-right">
                <strong>Recibo de Venta</strong><br>
                <span>FM {{ $orden->factura_numero }}</span><br>
                <span>Fecha: {{ $orden->created_at->format('Y-m-d') }}</span><br>
                <span>Hora: {{ $orden->created_at->format('H:i:s') }}</span>
            </td>
        </tr>
    </table>

    <!-- Cliente y vendedor -->
    <table style="margin-top: 10px;">
        <td>
            <strong>Cliente:</strong>{{ $orden->client->nombres ?? '' }} {{ $orden->client->apellidos ?? '' }}<br>
            <strong>NIT/CC:</strong> {{ $orden->client->numidentificacion ?? '' }}<br>
            <strong>Dirección:</strong> {{ $orden->client->ubicacion ?? 'N/A' }}<br>
            <strong>Teléfono:</strong> {{ $orden->client->telefono ?? 'N/A' }}
        </td>
        <td>
            <strong>Vendedor:</strong>{{ $orden->user->nombres ?? '' }} {{ $orden->user->apellidos ?? '' }}<br>
            <strong>CC:</strong> {{ $orden->user->numidentificacion ?? '' }}<br>
            <strong>Correo:</strong> {{ $orden->user->email ?? '' }}
        </td>

        </tr>
    </table>

    <!-- Productos -->
    <table style="margin-top: 15px;">
        <thead>
            <tr>
                <th>Código</th>
                <th>Descripción</th>
                <th>U/M</th>
                <th>Cant</th>
                <th class="text-right">V. Unit</th>
                <th class="text-right">Impuesto</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $subtotalGeneral = 0;
                $totalIVA = 0;
                $totalIBUA = 0;
                $totalIPC = 0;
            @endphp
            @foreach ($orden->details as $detalle)
                @php
                    $linea = $detalle->cantidad * $detalle->precio_unitario;
                    $iva = $detalle->iva ?? 0;
                    $ibua = $detalle->ibua ?? 0;
                    $ipc = $detalle->ipc ?? 0;
    
                    $subtotalGeneral += $linea;
                    $totalIVA += $iva;
                    $totalIBUA += $ibua;
                    $totalIPC += $ipc;
                    $valorImpuesto = $iva + $ibua + $ipc;
                @endphp
                <tr>
                    <td>{{ $detalle->producto->codigo_barras ?? 'N/A' }}</td>
                    <td>{{ $detalle->producto->nombre }}</td>
                    <td>{{ $detalle->producto->unidad ?? 'UND' }}</td>
                    <td class="text-center">{{ number_format($detalle->cantidad, 2) }}</td>
                    <td class="text-right">{{ number_format($detalle->precio_unitario, 2) }}</td>
                    <td class="text-center">{{ number_format($valorImpuesto, 2) }}</td>
                    <td class="text-right">{{ number_format($linea + $valorImpuesto, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <!-- Totales -->
    <table style="margin-top: 10px;">
        <tr>
            <td class="text-right"><strong>Subtotal:</strong></td>
            <td class="text-right">{{ number_format($subtotalGeneral, 2) }}</td>
        </tr>
        <tr>
            <td class="text-right"><strong>Total IVA:</strong></td>
            <td class="text-right">{{ number_format($totalIVA, 2) }}</td>
        </tr>
        @if ($totalIBUA > 0)
            <tr>
                <td class="text-right"><strong>Total IBUA:</strong></td>
                <td class="text-right">{{ number_format($totalIBUA, 2) }}</td>
            </tr>
        @endif
        @if ($totalIPC > 0)
            <tr>
                <td class="text-right"><strong>Total IMPOCONSUMO:</strong></td>
                <td class="text-right">{{ number_format($totalIPC, 2) }}</td>
            </tr>
        @endif
        <tr>
            <td class="text-right highlight">Total a Pagar:</td>
            <td class="text-right highlight">
                ${{ number_format($subtotalGeneral + $totalIVA + $totalIBUA + $totalIPC, 2) }}
            </td>
        </tr>
        <tr>
            <td class="text-right">Condición de Pago:</td>
            <td class="text-right">{{ $orden->forma_pago_nombre }} - {{ $orden->metodo_pago_nombre }}</td>
        </tr>
    </table>
    

    <!-- Observaciones -->
    <div class="observaciones">
        <p><strong>Observaciones:</strong> {{ $orden->observaciones ?? 'Gracias por su compra.' }}</p>
        <p>Este documento  de venta no es una factura.</p>
    </div>

</body>

</html>
