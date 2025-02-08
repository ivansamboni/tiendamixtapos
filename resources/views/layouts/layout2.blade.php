<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="author" content="">
    <meta name="keywords" content="">
    <meta name="description" content="">
    <title>@yield('title', 'Laravel + Bootstrap')</title>
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900" rel="stylesheet"
        defer>
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.css') }}">
    <script src="{{ asset('scripts/carritoCompras.js') }}"></script>
    <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}" defer></script>
    <link href="{{ asset('bootstrap/bootstrap-icons/font/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('bootstrap/app.css') }}" rel="stylesheet">

    @stack('styles')
</head>

<body>
    <nav class="navbar navbar-expand-md custom-navbar shadow-sm fixed-top">
        <div class="container">
            <!-- Logo alineado a la izquierda -->
            <a class="navbar-brand" href="/">
                <img src="{{ asset('archivos/images/logo.png') }}" alt="Logo" width="200">
            </a>

            <!-- Botón de colapso para dispositivos pequeños -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
                aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menú colapsable -->
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Categorías
                        </a>
                        <ul class="dropdown-menu">
                            @foreach ($categorias as $cat)
                                <li><a class="dropdown-item"
                                        href="{{ route('categoria.categoriatodo', ['id' => $cat['id'], 'slug' => $cat['slug']]) }}">{{ $cat['nombre'] }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Marcas
                        </a>
                        <ul class="dropdown-menu">
                            @foreach ($marcas as $mar)
                                <li><a class="dropdown-item"
                                        href="{{ route('marca.marcatodo', ['id' => $mar['id'], 'slug' => $mar['slug']]) }}">{{ $mar['nombre'] }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#">Mis Ordenes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contacto</a>
                    </li>
                    <form class="d-flex" role="search" action="{{ route('search.searchProducto') }}" method="GET">
                        <input class="form-control me-2 search-input" type="search" name="nombre" placeholder="Buscar"
                            value="{{ request('nombre') }}" aria-label="Search" required>
                        <button class="btn btn-search" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>
                </ul>
                <button class="btn btn-danger" href="#" data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"><i
                        class="bi bi-cart-check"></i></button> <span
                    class="translate-middle badge rounded-pill bg-danger">
                    <div id="spamcantidad">0</div>
                    <span class="visually-hidden">unread messages</span>

            </div>
        </div>
    </nav>
    <br>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasRightLabel"><i class="bi bi-cart-check"></i> Lista de compra
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form action="{{ route('carrito.show') }}" method="POST" id="carritoForm">
                @csrf
                <button type="submit" class="btn btn-success btn-sm w-100">
                    <i class="bi bi-cart-check"></i> Ir al carrito
                </button>
                <br><br><br>
            </form>
        </div>
        <button class="btn btn-danger btn-sm" onclick="vaciarcarro()"><i class="bi bi-cart-x"></i> Vaciar
            carrito</button>
    </div>
    </div>
    <br><br><br>
    <div class="container" style=" max-width: 90%">
        @yield('content') <!-- Aquí se insertará el contenido de las vistas -->
    </div>


</body>

</html>
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
                <div class="custom-card product-card mb-3" style="width: 100%;">
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


////////new

@extends('layouts.layout')

@section('title', 'Página de Inicio')

@section('content')
    <div id="hot-deal" class="section">
        <!-- container -->
        <div class="container">
            <!-- row -->
            <div class="row">
                <div class="col-md-12">
                    <div class="hot-deal">
                        <ul class="hot-deal-countdown">
                            <li>
                                <div>
                                    <h3>02</h3>
                                    <span>Days</span>
                                </div>
                            </li>
                            <li>
                                <div>
                                    <h3>10</h3>
                                    <span>Hours</span>
                                </div>
                            </li>
                            <li>
                                <div>
                                    <h3>34</h3>
                                    <span>Mins</span>
                                </div>
                            </li>
                            <li>
                                <div>
                                    <h3>60</h3>
                                    <span>Secs</span>
                                </div>
                            </li>
                        </ul>
                        <h2 class="text-uppercase">hot deal this week</h2>
                        <p>New Collection Up to 50% OFF</p>
                        <a class="primary-btn cta-btn" href="#">Shop now</a>
                    </div>
                </div>
            </div>
            <!-- /row -->
        </div>
        <!-- /container -->
    </div>
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
                                    <p class="card-text text-success">${{ number_format($pro['precio']) }}</p><br>
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
                <div class="product-item men" style="width: 100%;">
                    <div class="product">
                        <div class="flex-grow-1 d-flex flex-column align-items-center">
                            <img src="{{ asset('archivos/folder_img_product/' . ($pro['img1'] ?? 'sinimagen.jpg')) }}"
                                alt="{{ $pro['nombre'] }}" style="height: 100px;object-fit;">
                        </div>

                        <div class="product-body">
                            <p class="product-category"><a class="nav-link text-primary"
                                    href="{{ route('marca.marcatodo', ['id' => $pro->marca_id ?? $pro['id'], 'slug' => $pro->marca->slug ?? Str::slug($pro->marca->nombre ?? 'sin-marca')]) }}">{{ $pro->marca->nombre ?? '' }}</a>
                            </p>
                            <h3 class="product-name"> <a
                                    href="{{ route('producto.productodetalle', ['id' => $pro['id'], 'slug' => $pro['slug']]) }}">{{ $pro['nombre'] }}</a>
                            </h3>
                            <h4 class="product-price">${{ number_format($pro['precio']) }}</h4>
                        </div>

                        <div class="add-to-cart">
                            <button class="add-to-cart-btn" data-id="{{ $pro['id'] }}"
                                data-nombre="{{ $pro['nombre'] }}" data-precio="{{ $pro['precio'] }}"
                                data-img1="{{ asset('archivos/folder_img_product/' . ($pro['img1'] ?? 'sinimagen.jpg')) }}"
                                onclick="capturarDatos(this)"><i class="fa fa-shopping-cart"></i>Agregar</button>
                        </div>

                    </div>
                </div>

            </div>
        @endforeach
    </div>
    <br>
    <br>



    <br>
    <br>

    <div class="d-flex justify-content-center my-4">
        {{ $productoall->links('vendor.pagination.custom') }}
    </div>




@endsection
