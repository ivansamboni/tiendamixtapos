@extends('layouts.layout')

@section('title', 'Página de Inicio')

@section('content')
    <br>
    <br>
    <h3 class="text-center">{{ $marca->nombre ?? '' }}</h3>
    <br>

    <div class="row justify-content-center">
        @foreach ($productos as $pro)
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-4 d-flex">
                <div class="card product-card rounded-0 mx-auto" style="width: 100%;">
                    <br>
                    <a class="nav-link text-primary" href="{{ route('producto.productodetalle', ['id' => $pro['id']]) }}">
                        <div class="d-flex flex-column align-items-center">
                            <img src="{{ asset('archivos/folder_img_product/' . ($pro['img1'] ?? 'sinimagen.jpg')) }}"
                                alt="{{ $pro['nombre'] }}" style="height: 100px; object-fit: cover;">
                            <div class="text-center">{{ $pro['nombre'] }}
                    </a>
                    <a class="nav-link text-primary"
                        href="{{ route('marca.marcatodo', ['id' => $pro->marca_id ?? '0']) }}">{{ $pro->marca->nombre ?? '' }}</a>
                    <p class="card-text">${{ number_format($pro['precio']) }}</p>
                </div>
            </div>
    </div>
    </div>
    @endforeach


    <br>
    <br>
    {{ $productos->links() }}

@endsection
