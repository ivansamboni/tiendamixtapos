@extends('layouts.layout')

@section('title', 'Página de Inicio')

@section('content')
    <br>
    <br>
    <br>
    <h3 class="text-center">Productos Recientes</h3>
    <br>
    <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            @foreach ($productos->chunk(6) as $chunk)
                <!-- Divide los productos en grupos de 5 -->
                <div class="carousel-item @if ($loop->first) active @endif">
                    <div class="row justify-content-center">
                        @foreach ($chunk as $pro)
                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-3 d-flex">
                                <div class="mx-auto" style="width: 100%;">
                                    <br>
                                    <div class="text-center">
                                        <h6>Categoría <a class="nav-link text-primary"
                                                href="#">{{ $pro->categoria->nombre ?? '' }}</a> </h6>
                                                <img src="{{ asset('archivos/folder_img_product/' . ($pro['img1'] ?? 'sinimagen.jpg')) }}" 
                                                class="card-img-top" alt="{{ $pro['nombre'] }}" 
                                                style="height: 200px; object-fit: cover;">
                                        <h6>Marca
                                            <a class="nav-link text-primary"
                                                href="#">{{ $pro->marca->nombre ?? '' }}</a>
                                        </h6>
                                        <a class="nav-link text-primary" href="#">{{ $pro['nombre'] }}</a>
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
        <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
    <br>
    <br>
    <h3 class="text-center">Nuestros Productos</h3>
    <br>

    <div class="row justify-content-center">
        @foreach ($productoall as $pro)
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-8 d-flex">
               
                <div class="card mx-auto" style="width: 100%;">
                    <br>
                    <div class="text-center">
                        <h6>Categoría <a class="nav-link text-primary"
                                href="#">{{ $pro->categoria->nombre ?? '' }}</a> </h6>
                    </div>
                    <img src="{{ asset('archivos/folder_img_product/' . ($pro['img1'] ?? 'sinimagen.jpg')) }}" class="card-img-top"
                        alt="{{ $pro['nombre'] }}" style="height: 200px; object-fit: cover;">
                    <div class="card-body text-center">
                        <h6>Marca
                            <a class="nav-link text-primary" href="#">{{ $pro->marca->nombre ?? '' }}</a>
                        </h6>
                        <a class="nav-link text-primary" href="#">{{ $pro['nombre'] }}</a>
                        <p class="card-text">${{ number_format($pro['precio'], 2) }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <br>
    <br>
    {{ $productoall->links() }}

@endsection
