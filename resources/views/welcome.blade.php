@extends('layouts.layout')

@section('title', 'Página de Inicio')

@section('content')
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
    <br>
    <br>
    <h3 class="text-center">Nuestros Productos</h3>
    <br>

    <div class="row justify-content-center">
        @foreach ($productoall as $pro)
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-4 d-flex">
                <div class="mx-auto" style="width: 100%;">
                    <br>
                    <div class="text-center">
                        <a class="nav-link text-primary"
                            href="{{ route('categoria.categoriatodo', ['id' => $pro->categoria_id ?? '0']) }}">
                            {{ $pro->categoria->nombre ?? '' }}
                        </a>

                    </div>
                    <a class="nav-link text-primary" href="{{ route('producto.productodetalle', ['id' => $pro['id']]) }}">
                        <img src="{{ asset('archivos/folder_img_product/' . ($pro['img1'] ?? 'sinimagen.jpg')) }}"
                            class="card-img-top" alt="{{ $pro['nombre'] }}" style="height: 200px; object-fit: cover;">
                        <div class="text-center">
                            {{ $pro['nombre'] }}
                    </a>
                    <a class="nav-link text-primary"
                        href="{{ route('marca.marcatodo', ['id' => $pro->marca_id ?? '0']) }}">{{ $pro->marca->nombre ?? '' }}</a>
                    <p class="card-text">${{ number_format($pro['precio']) }}</p>

                </div>
            </div>
       </div>
    @endforeach
    </div>

    <br>
    <br>

    <div class="d-flex justify-content-center my-4">
        {{ $productoall->links('vendor.pagination.custom') }}
    </div>




@endsection
