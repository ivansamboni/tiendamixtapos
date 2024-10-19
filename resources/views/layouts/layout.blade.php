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

    <!-- CSS de Bootstrap -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.css') }}">

    <!-- Scripts de Bootstrap -->
    <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}" defer></script>
    <link href="{{ asset('bootstrap/bootstrap-icons/font/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('bootstrap/app.css') }}" rel="stylesheet">

    @stack('styles') <!-- Para estilos adicionales desde las vistas -->
</head>

<body>
    <nav class="navbar navbar-expand-md custom-navbar shadow-sm">
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
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Categorías
                        </a>
                        <ul class="dropdown-menu">
                            @foreach ($categorias as $cat)
                                <li><a class="dropdown-item" href="{{ route('categoria.categoriatodo', ['id' => $cat['id'], 'slug' => $cat['slug']]) }}">{{ $cat['nombre'] }}</a></li>
                            @endforeach
                        </ul>
                    </li>
    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Marcas
                        </a>
                        <ul class="dropdown-menu">
                            @foreach ($marcas as $mar)
                                <li><a class="dropdown-item" href="{{ route('marca.marcatodo', ['id' => $mar['id'], 'slug' => $mar['slug']]) }}">{{ $mar['nombre'] }}</a></li>
                            @endforeach
                        </ul>
                    </li>
    
                    <li class="nav-item">
                        <a class="nav-link" href="#">Mis Ordenes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contacto</a>
                    </li>
                </ul>
    
                <!-- Formulario de búsqueda -->
                <form class="d-flex" role="search" action="{{ route('search.searchProducto') }}" method="GET">
                    <input class="form-control me-2 search-input" type="search" name="nombre" placeholder="Buscar" value="{{ request('nombre') }}" aria-label="Search" required>
                    <button class="btn btn-search" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </form>                
            </div>
        </div>
    </nav>   
    <br>
    <div class="container mt-4">
        @yield('content') <!-- Aquí se insertará el contenido de las vistas -->
    </div>

    @stack('scripts') <!-- Para scripts adicionales desde las vistas -->
</body>

</html>
