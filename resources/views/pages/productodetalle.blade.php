@extends('layouts.layout')

@section('title', 'Página de Inicio')

@section('content')
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card rounded-0">
                    <div class="row g-0">
                        <!-- Columna de imágenes del producto -->
                        <div class="col-md-6">
                            <div class="container my-4">
                                <div class="row">
                                    <!-- Thumbnails (de arriba hacia abajo) -->
                                    <div class="col-md-12 ">
                                        <img src="{{ $producto->img1 ? asset('archivos/folder_img_product/' . $producto->img1) : asset('archivos/folder_img_product/sinimagen.jpg') }}"
                                            alt="" style="height: 80px; object-fit: cover;"
                                            onmouseover="changeImage(this.src)" class="thumbnail rounded">
                                        <img src="{{ $producto->img2 ? asset('archivos/folder_img_product/' . $producto->img2) : asset('archivos/folder_img_product/sinimagen.jpg') }}"
                                            alt="" style="height: 80px; object-fit: cover;"
                                            onmouseover="changeImage(this.src)" class="thumbnail rounded">
                                        <img src="{{ $producto->img3 ? asset('archivos/folder_img_product/' . $producto->img3) : asset('archivos/folder_img_product/sinimagen.jpg') }}"
                                            alt="" style="height: 80px; object-fit: cover;"
                                            onmouseover="changeImage(this.src)" class="thumbnail rounded">
                                        <img src="{{ $producto->img4 ? asset('archivos/folder_img_product/' . $producto->img4) : asset('archivos/folder_img_product/sinimagen.jpg') }}"
                                            alt="" style="height: 80px; object-fit: cover;"
                                            onmouseover="changeImage(this.src)" class="thumbnail rounded">
                                    </div>
                                    <div class="col-md-12 text-center">
                                        <!-- Imagen principal -->
                                        <img id="mainImage"
                                            src="{{ asset('archivos/folder_img_product/' . $producto->img1) }}"
                                            alt="{{ $producto->nombre }}" style="height: 300px; object-fit: cover;"
                                            class="product-img rounded mb-3 thumbnail" data-bs-toggle="modal"
                                            data-bs-target="#ampliarimagen" onclick="ampliarImage(this.src)">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Columna de detalles del producto -->
                        <div class="col-md-6 p-4">
                            <h3 class="card-title">{{ $producto->nombre }}</h3>
                            <a class="nav-link text-primary"
                                href="{{ route('categoria.categoriatodo', ['id' => $producto->categoria_id ?? '0']) }}">
                                {{ $producto->categoria->nombre ?? '' }}
                            </a>
                            <a class="nav-link text-primary"
                            href="{{ route('marca.marcatodo', ['id' => $producto->marca_id ?? '0']) }}">{{ $producto->marca->nombre ?? '' }}</a>
                            <p class="price">${{ number_format($producto->precio) }}</p>

                            <div class="d-grid gap-2">
                                <button class="botonCompra">Comprar Ahora</button>
                                <button class="btn btn-danger btn-outline">Agregar al Carrito</button>
                                <br>
                                <h6 class="card-title">Cantidad disponible {{ $producto->stock }}</h6>
                                <p>en 3 cuotas de 
                                    $
                                    37.633
                                     con 0% interés
                                    
                                    Ver los medios de pago
                                    Cupón10% OFF. Compra mínima $1.000.
                                    Envío gratis a todo el país
                                    
                                    Conoce los tiempos y las formas de envío.
                                    
                                    Calcular cuándo llega</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Descripción del producto -->
                <br>
                <div class="card rounded-0">
                    <div class="card-body">
                        <h5 class="card-title navbar-brand">Descripción</h5>
                        <p class="text-left">{!! nl2br(e($producto->descripcion)) !!}</p>
                        <p class="card-text">
                            <small class="text-muted">{{ $producto->created_at }}</small>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- modal imagen apliada -->
    <!-- Modal -->
    <div class="modal fade" id="ampliarimagen" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg rounded-0">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img id="modalImage" src="{{ asset('archivos/folder_img_product/' . $producto->img1) }}"
                        alt="{{ $producto->nombre }}" style="height: 400px; object-fit: cover;"
                        class="product-img rounded mb-3 text-center">
                </div>
            </div>
        </div>
    </div>

    <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            @foreach ($productos->chunk(5) as $chunk)
                <!-- Divide los productos en grupos de 5 -->
                <div class="carousel-item @if ($loop->first) active @endif">
                    <div class="row justify-content-center">
                        @foreach ($chunk as $pro)
                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-3 d-flex">
                                <div class="mx-auto" style="width: 100%;">
                                    <br>
                                    <div class="text-center">
                                        <a class="nav-link text-dark"
                                            href="{{ route('producto.productodetalle', ['id' => $pro['id']]) }}">
                                            <img src="{{ asset('archivos/folder_img_product/' . ($pro['img1'] ?? 'sinimagen.jpg')) }}"
                                                class="card-img-top" alt="{{ $pro['nombre'] }}"
                                                style="height: 200px; object-fit: cover;">

                                            {{ $pro['nombre'] }}
                                        </a>

                                        <p class="card-text">${{ number_format($pro['precio'], 2) }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Controles del carrusel -->
        <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
    <script>
        function changeImage(src) {
            document.getElementById('mainImage').src = src;
        }

        function ampliarImage(src) {
            document.getElementById('modalImage').src = src;
        }
    </script>
@endsection
