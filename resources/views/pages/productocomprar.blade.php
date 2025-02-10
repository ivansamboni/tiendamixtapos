@extends('layouts.layout')

@section('title', 'Página de Inicio')

@section('content')
<script src="https://sdk.mercadopago.com/js/v2"></script>
    <div class="row mt-2">
        <!-- Columna del formulario -->
        <div class="col-md-6">
            <form action="{{ route('order.store') }}" method="POST" enctype="multipart/form-data" id="paymentForm">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-4">Procesar pago </h4>

                        <!-- Ejemplo de productos -->
                        <input type="text" hidden name="productos[0][id]" value="{{ $producto->id }}" id="productoid">
                        <input type="text" hidden name="productos[0][cantidad]" value="1" id="productocantidad">
                        <div class="row g-3">
                            <!-- Nombres -->
                            <div class="col-md-6">
                                <label for="nombres" class="form-label">Nombres</label>
                                <input type="text" class="form-control form-control-sm" id="nombres" name="nombres"
                                    required>
                            </div>

                            <!-- Apellidos -->
                            <div class="col-md-6">
                                <label for="apellidos" class="form-label">Apellidos</label>
                                <input type="text" class="form-control form-control-sm" id="apellidos" name="apellidos"
                                    required>
                            </div>

                            <!-- Cédula -->
                            <div class="col-md-6">
                                <label for="cedula" class="form-label">Cédula</label>
                                <input type="text" class="form-control form-control-sm" id="cedula" name="cedula"
                                    required>
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control form-control-sm" id="email" name="email"
                                    required>
                            </div>

                            <!-- Teléfono -->
                            <div class="col-md-6">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="text" class="form-control form-control-sm" id="telefono" name="telefono"
                                    required>
                            </div>

                            <!-- Departamento -->
                            <div class="col-md-6">
                                <label for="tipo_checkout" class="form-label">Departamento</label>
                                <select class="form-select" id="departamento" name="departamento" required>
                                    <option value="">Selecciona un Departamento</option>
                                    <option value="Amazonas">Amazonas</option>
                                    <option value="Antioquia">Antioquia</option>
                                    <option value="Arauca">Arauca</option>
                                    <option value="Atlántico">Atlántico</option>
                                    <option value="Bolívar">Bolívar</option>
                                    <option value="Boyacá">Boyacá</option>
                                    <option value="Caldas">Caldas</option>
                                    <option value="Caquetá">Caquetá</option>
                                    <option value="Casanare">Casanare</option>
                                    <option value="Cauca">Cauca</option>
                                    <option value="Cesar">Cesar</option>
                                    <option value="Chocó">Chocó</option>
                                    <option value="Córdoba">Córdoba</option>
                                    <option value="Cundinamarca">Cundinamarca</option>
                                    <option value="Guainía">Guainía</option>
                                    <option value="Guaviare">Guaviare</option>
                                    <option value="Huila">Huila</option>
                                    <option value="La Guajira">La Guajira</option>
                                    <option value="Magdalena">Magdalena</option>
                                    <option value="Meta">Meta</option>
                                    <option value="Nariño">Nariño</option>
                                    <option value="Norte de Santander">Norte de Santander</option>
                                    <option value="Putumayo">Putumayo</option>
                                    <option value="Quindío">Quindío</option>
                                    <option value="Risaralda">Risaralda</option>
                                    <option value="San Andrés y Providencia">San Andrés y Providencia</option>
                                    <option value="Santander">Santander</option>
                                    <option value="Sucre">Sucre</option>
                                    <option value="Tolima">Tolima</option>
                                    <option value="Valle del Cauca">Valle del Cauca</option>
                                    <option value="Vaupés">Vaupés</option>
                                    <option value="Vichada">Vichada</option>
                                </select>
                            </div>

                            <!-- Ciudad -->
                            <div class="col-md-6">
                                <label for="ciudad" class="form-label">Ciudad</label>
                                <input type="text" class="form-control form-control-sm" id="ciudad" name="ciudad"
                                    required>
                            </div>

                            <!-- Dirección -->
                            <div class="col-md-6">
                                <label for="direccion" class="form-label">Dirección</label>
                                <input type="text" class="form-control form-control-sm" id="direccion"
                                    name="direccion" required>
                            </div>

                            <!-- Comprobante de Pago -->
                            <div class="col-md-12">
                                <label for="comprobante" class="form-label">Comprobante de Pago</label>
                                <input type="file" class="form-control form-control-sm" id="comprobante"
                                    name="comprobante_pago" required>
                            </div>
                        </div>
                        <br>
                        <!-- Botón Enviar -->

                    </div>
                </div>
        </div>
        <!-- Columna del producto -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 d-flex align-items-center gap-3">
                            <img src="{{ asset('archivos/folder_img_product/' . ($producto['img1'] ?? 'sinimagen.jpg')) }}"
                                alt="{{ $producto['nombre'] }}" style="height: 80px; object-fit: cover;">
                            <a class="nav-link text-primary"
                                href="{{ route('producto.productodetalle', ['id' => $producto['id'], 'slug' => $producto['slug']]) }}">
                                <small>{{ $producto['nombre'] }}</small></a>

                            <div class="col-4">
                                <p> Disponibles {{ $producto['stock'] }}</p>
                                <h5 class="card-text text-success">Precio ${{ number_format($producto['precio']) }}</h5>

                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-6">
                            <label for="cantidad">Cantidad</label>
                            <input type="number" class="form-control form-control-sm" name="cantidad"
                                id="agregarcantidad" value="1" min="1" max="{{ $producto['stock'] }}">

                            <input type="number" hidden id="precio" id="precio"
                                value="{{ $producto['precio'] }}"><br>
                        </div>

                        <hr>
                        <div class="row mt-2">
                            <div class="col-6">
                                <h3>Total</h3>
                                <h4 id="total" class="text-success">${{ $producto['precio'] }}</h4>
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="botonCompra form-control">Procesar Pago</button>
                               
                            </div>
                            <div class="d-flex">
                                <!-- Contenedor del botón -->
                                <div id="wallet_container"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        </form>
    </div>

   

<script>
        // Inicializa el objeto MercadoPago con el PUBLIC_KEY
        const mp = new MercadoPago('APP_USR-ed24d67b-50b9-48d6-9fc2-959a5c0157f2', {
            locale: 'es-MX'
        });

        // Crea un componente de billetera de MercadoPago en el contenedor con id "wallet_container"
        mp.bricks().create("wallet", "wallet_container", {
            initialization: {
                preferenceId: '<?php echo $preference->id; ?>',
                redirectMode: 'self'
            },
            customization: {
                texts: {
                    action: "pay",
                    valueProp: 'security_safety',
                },
            },
        });




        const cantidad = document.getElementById('productocantidad');
        const agregarcantidad = document.getElementById('agregarcantidad');
        const precio = document.getElementById('precio');
        const total = document.getElementById('total');

        const valores = () => {
            cantidad.value = agregarcantidad.value;
            const cantidadNum = parseFloat(cantidad.value);
            const precioNum = parseFloat(precio.value);
            let Total = cantidadNum * precioNum;
            document.getElementById('total').textContent = `$${parseFloat(Total).toLocaleString('es-ES')}`;
        };

        agregarcantidad.addEventListener('input', valores);
        window.onload = function() {
            if (performance.navigation.type === 2) {
                // Limpia el formulario si el usuario vuelve atrás
                document.getElementById('paymentForm').reset();
                agregarcantidad.value = 1
            }
        };
    </script>

@endsection
