@extends('layouts.layout')

@section('title', 'Página de Inicio')

@section('content')
    <br>
    <br>        
    <h3 class="text-center">{{$marca->nombre ?? ''}}</h3>
    <br>

    <div class="row justify-content-center">
        @foreach ($productos as $pro)
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-4 d-flex">
                <div class="mx-auto" style="width: 100%;">
                    <br>
                    <div class="text-center">
                        <a class="nav-link text-primary"
                        href="{{ route('categoria.categoriatodo', ['id' => $pro->categoria_id ?? '0']) }}">
                        {{ $pro->categoria->nombre ?? '' }}
                    </a>
                    </div>
                    <img src="{{ asset('archivos/folder_img_product/' . ($pro['img1'] ?? 'sinimagen.jpg')) }}"
                        class="card-img-top" alt="{{ $pro['nombre'] }}" style="height: 200px; object-fit: cover;">
                    <div class="text-center">
                        <a class="nav-link text-primary" href="#">{{ $pro->marca->nombre ?? '' }}</a>
                        <a class="nav-link text-primary" href="{{ route('producto.productodetalle', ['id' => $pro['id']]) }}">
                            {{ $pro['nombre'] }}
                        </a>
                        <p class="card-text">${{ number_format($pro['precio']) }}</p>
                        
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <br>
    <br>
    {{ $productos->links() }}

@endsection
