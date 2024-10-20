@extends('layouts.layout')

@section('title', 'Página de Inicio')

@section('content')
    <br>
    <br>
    <h3 class="text-center">{{ $categoria->nombre ?? '' }}</h3>
    <br>

    <div class="row justify-content-center">
        @foreach ($productos as $pro)
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-4 d-flex">
                <div class="card product-card rounded-0 mx-auto" style="width: 100%;">
                    <br>
                    <div class="card-body flex-grow-1 d-flex flex-column align-items-center" >
                        
                            <a class="nav-link text-primary"
                                href="{{ route('categoria.categoriatodo', ['id' => $pro->categoria_id ?? $pro['id'], 'slug' => $pro->categoria->slug ?? Str::slug($pro->categoria->nombre ?? 'sin-categoria')]) }}">
                                {{ $pro->categoria->nombre ?? '' }}
                            </a>
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
    {{ $productos->links() }}

@endsection
