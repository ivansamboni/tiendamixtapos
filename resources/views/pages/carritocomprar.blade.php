@extends('layouts.layout')

@section('title', 'Página de Inicio')

@section('content')
<script src="https://sdk.mercadopago.com/js/v2"></script>
    <div class="row mt-2">
        <!-- Columna del formulario -->
        <div class="col-md-6">
            <div class="custom-card card">
                <div class="card-body">
                    <h4 class="mb-4">Procesar pago </h4>
                    <form action="{{ route('create.preference') }}" method="POST" onsubmit="vaciarcarro()"
                        enctype="multipart/form-data" id="paymentForm">
                        @csrf
                        <!-- Ejemplo de productos -->
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
                                <input type="text" class="form-control form-control-sm" id="direccion" name="direccion"
                                    required>
                            </div>

                            <!-- Comprobante de Pago -->
                            <div class="col-md-12">
                                <label for="comprobante" class="form-label">Comprobante de Pago</label>
                                <input type="file" class="form-control form-control-sm" id="comprobante"
                                    name="comprobante_pago" >
                            </div>
                        </div>
                        <br>
                        <div class="alert alert-danger" role="alert">
                            <p> Consiganción Bancaria
                                Consigna o transfiere el valor total de la orden a cualquiera de nuestras cuentas:

                                BANCOLOMBIA
                                Cuenta de ahorros: 69893423117
                                Titular: Ricardo Arcila


                                Al confirmar el pago, procesaremos la orden y haremos el envío lo más rápido posible.</p>
                        </div>
                </div>
            </div>
        </div>
        <!-- Columna del producto -->
        <div class="col-md-6">
            <div class="custom-card card">
                <div class="card-body">
                    @foreach ($productos as $index => $producto)
                        <div class="row product-group align-items-center mb-3" id="product-{{ $index }}">
                            <!-- Imagen del producto -->
                            <div class="col-2">
                                <img src="{{ asset('archivos/folder_img_product/' . ($producto['img1'] ?? 'sinimagen.jpg')) }}"
                                    alt="Imagen de {{ $producto['nombre'] }}" class="img-fluid rounded"
                                    style="height: 80px; object-fit: cover;">
                            </div>

                            <!-- Nombre y precio del producto -->
                            <div class="col-6">
                                <small class="d-block">{{ $producto['nombre'] }}</small>
                                <h5 class="card-text text-success">
                                    ${{ number_format($producto['precio']) }}
                                </h5>
                            </div>

                            <!-- Cantidad del producto -->
                            <div class="col-3">
                                <small class="d-block">
                                    Disponibles {!! $producto->stock > 0 ? $producto->stock : 
                                    '<small class="text-danger">Se agotó</small>' !!}

                                </small>
                                <input type="hidden" name="productos[{{ $index }}][id]"
                                    value="{{ $producto['id'] }}">
                                <input type="number" class="form-control form-control-sm"
                                    name="productos[{{ $index }}][cantidad]" value="1" min="1"
                                    max="{{ $producto['stock'] }}" onchange="updateTotal()">
                                <input type="hidden" name="productos[{{ $index }}][precio]"
                                    value="{{ $producto['precio'] }}">
                            </div>

                            <!-- Botón de eliminar -->
                            <div class="col-1 d-flex justify-content-end">
                                <button type="button" class="btn btn-danger btn-sm"
                                    onclick="removeProduct({{ $index }}, {{ $producto['id'] }})">X</button>
                            </div>
                        </div>
                    @endforeach
                    <!-- Total y botón de compra -->
                    <div class="text-center mt-4">
                        <h3>Total</h3>
                        <h4 id="total" class="text-success">$0</h4>
                        <button type="submit" class="form-control">Pagar con Mercado Pago</button>
                        
                    </div>
                </div>
            </div>
        </div>

    </div>   

@endsection
