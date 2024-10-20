@extends('layouts.layout')

@section('title', 'Página de Inicio')

@section('content')

    <div id="productCarousel" class="carousel carousel-dark slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            @foreach ($productos->chunk(3) as $chunk)
                <!-- Divide los productos en grupos de 5 -->
                <div class="carousel-item @if ($loop->first) active @endif">
                    <div class="row justify-content-center">
                        @foreach ($chunk as $pro)
                            <div class="col-12 col-sm-6 col-md-4 mb-4 d-flex">
                                <div class="custom-card product-card rounded-0 mx-auto" style="width: 100%;">
                                    <br>
                                    <div class="text-center">

                                    </div>
                                    <a class="nav-link text-dark"
                                        href="{{ route('producto.productodetalle', ['id' => $pro['id'], 'slug' => $pro['slug']]) }}">
                                        <div class="d-flex flex-column align-items-center">
                                            <img src="{{ asset('archivos/folder_img_product/' . ($pro['img1'] ?? 'sinimagen.jpg')) }}"
                                                alt="{{ $pro['nombre'] }}" style="height: 200px; object-fit;">
                                            <div class="text-center">{{ $pro['nombre'] }}
                                    </a>
                                    <a class="nav-link text-primary"
                                        href="{{ route('marca.marcatodo', ['id' => $pro->marca_id ?? $pro['id'], 'slug' => $pro->marca->slug ?? Str::slug($pro->marca->nombre ?? 'sin-marca')]) }}">{{ $pro->marca->nombre ?? '' }}</a>
                                    <p class="card-text text-success">${{ number_format($pro['precio']) }}</p>
                                </div>
                            </div>
                    </div>
                </div>
            @endforeach
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
    @endforeach
    </div>
    </div>

    <br>
    <h3 class="text-center">Nuestros Productos</h3>
    <br>

    <div class="row justify-content-center">
        @foreach ($productoall as $pro)
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-4 d-flex">
                <div class="custom-card product-card rounded-0 mx-auto" style="width: 100%;">
                    <br>
                    <div class="card-body flex-grow-1 d-flex flex-column align-items-center">

                        <a class="nav-link text-primary"
                            href="{{ route('producto.productodetalle', ['id' => $pro['id'], 'slug' => $pro['slug']]) }}">
                            <img src="{{ asset('archivos/folder_img_product/' . ($pro['img1'] ?? 'sinimagen.jpg')) }}"
                                alt="{{ $pro['nombre'] }}" style="height: 100px; object-fit: cover;">
                            <div class="text-center">{{ $pro['nombre'] }}
                        </a>
                        <a class="nav-link text-primary"
                            href="{{ route('marca.marcatodo', ['id' => $pro->marca_id ?? $pro['id'], 'slug' => $pro->marca->slug ?? Str::slug($pro->marca->nombre ?? 'sin-marca')]) }}">{{ $pro->marca->nombre ?? '' }}</a>
                        <p class="card-text">${{ number_format($pro['precio']) }}</p>

                    </div>
                    <div class="card-footer mt-auto p-0">
                        <button class="btn btn-danger btn-sm w-100" data-id="{{ $pro['id'] }}"
                            data-nombre="{{ $pro['nombre'] }}" data-precio="{{ $pro['precio'] }}"
                            data-img1="{{ asset('archivos/folder_img_product/' . ($pro['img1'] ?? 'sinimagen.jpg')) }}"
                            onclick="capturarDatos(this)">
                            <i class="bi bi-cart-check"></i> Agregar </button>
                    </div>
                </div>
            </div>

    </div>
    @endforeach
    <br>
    <br>

    <div class="d-flex justify-content-center my-4">
        {{ $productoall->links('vendor.pagination.custom') }}
    </div>




@endsection
