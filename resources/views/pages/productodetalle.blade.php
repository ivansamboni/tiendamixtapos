@extends('layouts.layout')

@section('title', 'Página de Inicio')

@section('content')

    <div class="section">
        <!-- container -->
        <div class="container">
            <!-- row -->
            <div class="row">


                <!-- Product thumb imgs -->

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
                                <img id="mainImage" src="{{ asset('archivos/folder_img_product/' . $producto->img1) }}"
                                    alt="{{ $producto->nombre }}" style="height: 300px; object-fit: cover;"
                                    class="product-img rounded mb-3 thumbnail" data-bs-toggle="modal"
                                    data-bs-target="#ampliarimagen" onclick="ampliarImage(this.src)">
                            </div>
                        </div>
                    </div>
                </div>


                <!-- /Product thumb imgs -->

                <!-- Product details -->
                <div class="col-md-5">
                    <div class="product-details">
                        <h2 class="product-name">{{ $producto->nombre }}</h2>
                        <div>
                            <h3 class="product-price">${{ number_format($producto->precio) }}</h3>
                            <h6>Disponibles <span class="product-available">{{ $producto->stock }}</span></h6>
                        </div>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut
                            labore
                            et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi
                            ut
                            aliquip ex ea commodo consequat.</p>
                        <div class="d-grid gap-2">                        
                            <button class="tn botonCompra text-center" data-id="{{ $producto['id'] }}"
                                data-nombre="{{ $producto['nombre'] }}" data-precio="{{ $producto['precio'] }}"
                                data-img1="{{ asset('archivos/folder_img_product/' . ($producto['img1'] ?? 'sinimagen.jpg')) }}"
                                onclick="capturarDatos(this)" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                                <i class="bi bi-cart-check"></i> Agregar </button>
                            <br>
                        </div>



                    </div>
                </div>
                <!-- /Product details -->

                <!-- Product tab -->
                <div class="col-md-12">
                    <div id="product-tab">
                        <!-- product tab nav -->
                        <ul class="tab-nav">
                            <li class="active"><a data-toggle="tab" href="#tab1">Descripción</a></li>

                        </ul>
                        <!-- /product tab nav -->
                        <p class="text-left">{!! nl2br(e($producto->descripcion)) !!}</p>
                        <!-- product tab content -->


                        <!-- /product tab content  -->
                    </div>
                </div>
                <!-- /product tab -->
            </div>
            <!-- /row -->
        </div>
        <!-- /container -->
    </div>
    <!-- Modal -->

    <div class="modal fade" id="ampliarimagen" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg rounded-0">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="btn-close justify-content-end" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                    <div class="d-flex justify-content-center align-items-center" style="height: 400px;">
                        <img id="modalImage" src="{{ asset('archivos/folder_img_product/' . $producto->img1) }}"
                            alt="{{ $producto->nombre }}" class="img-fluid" style="max-height: 100%; object-fit: contain;">
                    </div>
                </div>

            </div>
        </div>
    </div>
    <br>
    <div id="productCarousel" class="carousel carousel-dark slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            @foreach ($productos->chunk(5) as $chunk)
                <!-- Divide los productos en grupos de 5 -->
                <div class="carousel-item @if ($loop->first) active @endif">
                    <div class="row justify-content-center">
                        @foreach ($chunk as $pro)
                            <x-card-product :pro="$pro" />
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Controles del carrusel -->
        <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
          </button>
    </div>

@endsection
