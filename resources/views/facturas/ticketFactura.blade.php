<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Ticket de Venta</title>
    <style>
        @media print {
            @page {
                size: 58mm auto;
                margin: 0;
            }
        }

        body {
            font-family: monospace;
            font-weight: bold;
            font-size: 10px;
            width: 58mm;
            margin: 0;
            padding: 0;
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
            width: 70%;
            border-collapse: collapse;
        }

        .productos th,
        .productos td {
            padding: 2px 2px;/
        }

        .productos th {
            text-align: center;
            font-weight: bold;
            background-color: #f0f0f0;
        }

        .productos td {
            vertical-align: middle;
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
        <p>
        <h3 class="titulo">{{ $negocio->nombre ?? '' }}</h3>
        NIT: {{ $negocio->nit ?? '' }}
        TEL: {{ $negocio->telefonos ?? '' }}<br>
        {{ $negocio->direccion ?? '' }}</p>
        <p>Ticket de Venta #: {{ $orden->factura_numero }}</p>
        <p>Fecha: {{ $orden->created_at->format('d/m/Y H:i') }}</p>
        <p>Vendedor: {{ $orden->user->nombres ?? '' }}
            {{ $orden->user->apellidos ?? '' }}<br>{{ $orden->user->numidentificacion ?? '' }}</p>
        <p>Cliente: {{ $orden->client->nombres ?? '' }}
            {{ $orden->client->apellidos ?? '' }}<br>{{ $orden->client->numidentificacion ?? '' }}</p>
        <br>
        <table class="productos">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cant.</th>
                    <th>P.Uni</th>
                    <th>Iva</th>
                    <th>Monto</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalCantidad = 0;
                    $totalIVA = 0;
                    $totalIBUA = 0;
                    $totalIPC = 0;
                @endphp
                @foreach ($orden->details as $detalle)
                    @php
                        $totalCantidad += $detalle->cantidad;
                        $totalIVA += $detalle->iva;
                        $totalIBUA += $detalle->ibua;
                        $totalIPC += $detalle->ipc;
                        $subtotal = $detalle->precio_unitario + $detalle->iva + $detalle->ibua + $detalle->ipc;

                    @endphp
                    <tr>
                        <td>{{ $detalle->producto->nombre }}</td>
                        <td>
                            {{ fmod($detalle->cantidad, 1) == 0 ? intval($detalle->cantidad) : number_format($detalle->cantidad, 3, '.', '') }}
                        </td>
                        <td>{{ number_format($detalle->producto->precio_venta) }}</td>
                        <td>{{ number_format($detalle->producto->iva->valor ?? 0) }}</td>
                        <td>{{ number_format($detalle->cantidad * $detalle->precio_unitario + $detalle->iva + $detalle->ibua + $detalle->ipc) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br>
        <hr>
        <section>
            <h3>Resumen de Impuestos</h3>
            <table class="table table-borderless table-sm">
                <tbody>
                    <tr>
                        <td class="fw-bold">Total IVA:</td>
                        <td class="text-end">{{ number_format($totalIVA) }}</td>
                    </tr>
                    @if ($totalIBUA > 0)
                        <tr>
                            <td class="fw-bold">Total IBUA:</td>
                            <td class="text-end">{{ number_format($totalIBUA) }}</td>
                        </tr>
                    @endif
                    @if ($totalIPC > 0)
                        <tr>
                            <td class="fw-bold">Total IMPOCONSUMO:</td>
                            <td class="text-end">{{ number_format($totalIPC) }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </section>    
        <br>    
        <section>           
            @php
                $totalImpuestos = $totalIVA + $totalIBUA + $totalIPC;
                $subtotalGeneral = $orden->total - $totalImpuestos;
            @endphp
            <table class="table table-bordered table-sm">
                <tbody>
                    <tr>
                        <td class="fw-bold">Total Bruto:</td>
                        <td class="text-end">${{ number_format($subtotalGeneral) }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Vlr Impuestos:</td>
                        <td class="text-end">${{ number_format($totalImpuestos) }}</td>
                    </tr>                   
                    <tr class="table-primary">
                        <td class="fw-bold h5"><h3>Total General:</h3></td>
                        <td class="text-end fw-bold h5"><h3>${{ number_format($orden->total) }}</h3></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Forma de pago:</td>
                        <td class="text-end">{{ $orden->forma_pago_nombre }} - {{ $orden->metodo_pago_nombre }}</td>
                    </tr>
                </tbody>
            </table>
        </section>

        <h3>¡Gracias por su compra!</h3>
    </div>
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>

</html>
