@extends('layouts.layout')

@section('title', 'Página de Inicio')

@section('content')
    <script src="https://sdk.mercadopago.com/js/v2"></script>

    <div class="container">
        <br>
        <h3>Resumen de la Orden #{{ $orderId = session('orderId') }} </h3>
        <br><br>
        <div class="row">


            <div class="col-md-6">
                <!-- Datos del comprador -->
                <h4>Datos Personales</h4>
                <ul>
                    <li><strong>Nombres:</strong> {{ session('requestData.nombres', 'No disponible') }}</li>
                    <li><strong>Apellidos:</strong> {{ session('requestData.apellidos', 'No disponible') }}</li>
                    <li><strong>Cédula:</strong> {{ session('requestData.cedula', 'No disponible') }}</li>
                    <li><strong>Email:</strong> {{ session('requestData.email', 'No disponible') }}</li>
                    <li><strong>Teléfono:</strong> {{ session('requestData.telefono', 'No disponible') }}</li>
                    <li><strong>Departamento:</strong> {{ session('requestData.departamento', 'No disponible') }}</li>
                    <li><strong>Ciudad:</strong> {{ session('requestData.ciudad', 'No disponible') }}</li>
                    <li><strong>Dirección:</strong> {{ session('requestData.direccion', 'No disponible') }}</li>
                </ul>
            </div>

            <div class="col-md-6">
                <!-- Lista de productos -->
                <h4>Productos</h4>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio/U</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (session('requestData.productos', []) as $producto)
                            <tr>
                                <td>{{ $producto['nombre'] ?? 'Nombre no disponible' }}</td>
                                <td>{{ $producto['cantidad'] }}</td>
                                <td>${{ number_format($producto['precio'], 2) }}</td>
                                <td>${{ number_format($producto['cantidad'] * $producto['precio'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <br>

                <!-- Total de la orden -->
                <h4>Total a pagar: ${{ number_format(session('total', 0), 2) }}</h4>
            </div>
            <br>
            <br>

        </div>
        <div class="text-center">

            <div id="wallet_container"></div>
            <p>Con MercadoPago tienes varias opciones de pago, todas las transacciones son seguras y están encriptadas.</p>
            <img src="{{ asset('archivos/images/mercadopago.png') }}" width="60%" alt="">
        </div>
        <br>
        <div class="alert alert-success" role="alert">
            <p> Consiganción Bancaria
                Consigna o transfiere el valor total de la orden a cualquiera de nuestras cuentas:

                BANCOLOMBIA
                Cuenta de ahorros: 69893423117
                Titular: Ricardo Arcila


                Al confirmar el pago, procesaremos la orden y haremos el envío lo más rápido posible.</p>
        </div>

    </div>
    <script>
        const mp = new MercadoPago('APP_USR-ed24d67b-50b9-48d6-9fc2-959a5c0157f2', {
            locale: 'es-CO'
        });

        // Crea un componente de billetera de MercadoPago en el contenedor con id "wallet_container"
        document.addEventListener("DOMContentLoaded", function() {
            const preference = "{{ $preference = session('preference') }}";

            if (preference) {
                mp.bricks().create("wallet", "wallet_container", {
                    initialization: {
                        preferenceId: preference,
                        redirectMode: 'self'
                    },
                    customization: {
                        texts: {
                            action: "pay",
                            valueProp: 'security_safety',
                        },
                    },
                });
            } else {
                console.error("No se encontró preferenceId en la sesión.");
            }
        });
    </script>

    </div>
@endsection
