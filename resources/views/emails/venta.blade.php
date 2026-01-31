<div style="font-family: Arial, sans-serif; font-size: 14px; color: #333;">
    <div style="text-align: center; padding: 20px; border-bottom: 1px solid #ddd;">
        <img src="{{ $message->embed(public_path('archivos/images/' . ($negocio->logotipo ?? 'default.png'))) }}" alt="Logo" width="80" style="margin-bottom: 10px;">
        
        <h2 style="margin: 0; color: #222;">{{ $negocio->nombre ?? 'Nombre del Negocio' }}</h2>

        <p style="margin: 5px 0 0;">
            <strong>NIT:</strong> {{ $negocio->nit ?? '-' }}<br>
            <strong>Tel:</strong> {{ $negocio->telefonos ?? '-' }}<br>
            {{ $negocio->direccion ?? '-' }}
        </p>
    </div>

    <div style="padding: 20px;">
        <p style="margin-top: 0;">Hola <strong>{{ $orden->client->nombres }}</strong>,</p>

        <p>Te compartimos tu factura de venta en el archivo adjunto.</p>

        <p>Gracias por confiar en <strong>{{ $negocio->nombre }}</strong>. Esperamos que vuelvas pronto</p>

        <hr style="border: none; border-top: 1px solid #eee; margin: 30px 0;">

        <p style="font-size: 12px; color: #888; text-align: center;">
            Este es un mensaje automático, por favor no respondas directamente a este correo.
        </p>
    </div>
</div>

