<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <title>Electro - HTML Ecommerce Template</title> <!-- Google font -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700" rel="stylesheet">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.css') }}">

    <!-- Slick -->
    <link type="text/css" rel="stylesheet" href="{{ asset('electro-master/css/slick.css') }}" />
    <link type="text/css" rel="stylesheet" href="css/slick-theme.css') }}" />
    <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}" defer></script>
    <!-- nouislider -->
    <link type="text/css" rel="stylesheet" href="{{ asset('electro-master/css/nouislider.min.css') }}" />
    <!-- Font Awesome Icon -->
    <link rel="stylesheet" href="{{ asset('electro-master/css/font-awesome.min.css') }}">
    <!-- Custom stlylesheet -->
    <link href="{{ asset('bootstrap/bootstrap-icons/font/bootstrap-icons.css') }}" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="{{ asset('electro-master/css/style.css') }}" />
    <link href="{{ asset('bootstrap/app.css') }}" rel="stylesheet">
    <script src="{{ asset('scripts/carritoCompras.js') }}"></script>
</head>

<body>
    <!-- HEADER -->
    <header>

        <!-- /MAIN HEADER -->
    </header>
    <!-- /HEADER -->

    <nav class="navbar navbar-expand-lg custom-navbarTop shadow-sm">
        <a href="/" class="logo me-3">
            <img src="{{ asset('archivos/images/logo.png') }}" style="height: 80px; object-fit: cover;" alt="">
        </a>
        <div class="container-fluid">
            <!-- Social Media Icons -->            
                       
            <div class="d-flex align-items-center">
                 
                <a href="https://www.facebook.com/tiendaerotica.tentacionescali" target="_blank" class="me-3 text-success" title="WhatsApp">
                   <h2 class="text-light"><i class="bi bi-facebook"></i></h2> 
                </a>
                <a href="https://wa.me/573161038901" target="_blank" class="me-3 text-primary" title="Facebook">
                    <h2 class="text-light"><i class="bi bi-whatsapp"></i></h2>
                </a>
            </div>


            <!-- Centered Search Form -->
            <div class="collapse navbar-collapse justify-content-center" id="navbarContent">
                <form class="d-flex w-50"  action="{{ route('search.searchProducto') }}" method="GET">
                    <input class="form-control me-2 search-input" type="search" name="nombre" placeholder="Buscar"
                    value="{{ request('nombre') }}" aria-label="Search" required>
                    <button class="btn btn-search" type="submit"><i class="bi bi-search"></i></button>
                </form>
            </div>           
            <!-- Right Aligned Links -->
            <ul class="navbar-nav ms-auto">                
                <div class="header-ctn">
                    <!-- Cart -->
                    <div class="dropdown">
                        <a class="dropdown-toggle" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
                            aria-controls="offcanvasRight">
                            <h3 class="text-light" style="cursor: pointer;"><i class="fa fa-shopping-cart"></i>
                            </h3>
                            <div class="qty">
                                <div id="spamcantidad">0</div>
                            </div>
                        </a>
                    </div>
                </div>

            </ul>
        </div>
    </nav>   

    <!-- NAVIGATION -->
    <div class="container">
        <nav class="navbar navbar-expand-md custom-navbar shadow-sm">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
                aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="container-fluid">
                <div class="collapse navbar-collapse" id="navbarContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-tags"></i> CATEGORIAS
                            </a>
                            <ul class="dropdown-menu">
                                @foreach ($categorias as $cat)
                                    <li><a class="dropdown-item"
                                            href="{{ route('categoria.categoriatodo', ['id' => $cat['id'], 'slug' => $cat['slug']]) }}">{{ $cat['nombre'] }}</a>
                                    </li>
                                @endforeach

                            </ul>
                        </li>
                        @foreach ($categorias->slice(0, 8) as $cat)
                            <li class="nav-item"><a class="nav-link"
                                    href="{{ route('categoria.categoriatodo', ['id' => $cat['id'], 'slug' => $cat['slug']]) }}">{{ $cat['nombre'] }}</a>
                            </li>
                        @endforeach
                        <li>
                            <a class="nav-link" href="{{ route('searchOrden') }}">ORDENES</a>
                        </li>
                    </ul>

                </div>
            </div>
        </nav>
    </div>
    <div class="offcanvas
                                offcanvas-end" tabindex="-1" id="offcanvasRight"
        aria-labelledby="offcanvasRightLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasRightLabel"><i class="bi bi-cart-check"></i> Lista de compra
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="cart-list">
                <div class="product-widget">
                    <form action="{{ route('carrito.show') }}" method="POST" id="carritoForm">
                        @csrf
                        <br>

                        <button type="submit" class="myCar btn-sm w-100" @disabled(Route::is('carrito.show'))>
                            <i class="bi bi-cart-check"></i> Ir al carrito
                        </button>
                        <br><br>
                    </form>
                </div>
            </div>
        </div>
        <button class="btn btn-danger btn-sm" onclick="vaciarcarro()"><i class="bi bi-cart-x"></i> Vaciar
            carrito</button>
    </div>


    @yield('content')
    <br><br><br>
    <footer id="footer">
        <!-- top footer -->
        <div class="section">
            <!-- container -->
            <div class="container">
                <!-- row -->
                <div class="row">
                    <div class="col-md-3 col-xs-6">
                        <div class="footer">
                            <h3 class="footer-title">Quienes somos</h3>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                tempor
                                incididunt ut.</p>                           
                                                      
                           
                        </div>
                    </div>

                    <div class="col-md-3 col-xs-6">
                        <div class="footer">
                            <h3 class="footer-title">Categorías</h3>
                            <ul class="footer-links">
                                @foreach ($categorias->slice(0, 6) as $cat)
                                    <li><a class=""
                                            href="{{ route('categoria.categoriatodo', ['id' => $cat['id'], 'slug' => $cat['slug']]) }}">{{ $cat['nombre'] }}</a>
                                @endforeach
                            </ul>
                        </div>
                    </div>                 
                    <div class="col-md-3 col-xs-6">
                        <div class="footer">
                            <h3 class="footer-title">Nuestras redes sociales</h3>
                            <ul class="footer-links">
                                <a href="https://www.facebook.com/tiendaerotica.tentacionescali" target="_blank" class="me-3" title="facebook">
                                    <h4 class="text-light"><i class="bi bi-facebook"></i> Nuestro Facebook</h4> 
                                 </a>
                                 <a href="https://wa.me/573161038901" target="_blank" class="me-3" title="WhatsApp">
                                     <h4 class="text-light"><i class="bi bi-whatsapp"></i> Nuestro Whatsapp</h4>
                                 </a>    
                            </ul>
                        </div>
                    </div>         
                   
                </div>
                <!-- /row -->
            </div>
            <!-- /container -->
        </div>
        <!-- /top footer -->

        <!-- bottom footer -->
        <div id="bottom-footer" class="section">
            <div class="container">
                <!-- row -->
                <div class="row">
                    <div class="col-md-12 text-center">
                        <ul class="footer-payments">
                            <li><a href="#"><i class="fa fa-cc-visa"></i></a></li>
                            <li><a href="#"><i class="fa fa-credit-card"></i></a></li>
                            <li><a href="#"><i class="fa fa-cc-paypal"></i></a></li>
                            <li><a href="#"><i class="fa fa-cc-mastercard"></i></a></li>
                            <li><a href="#"><i class="fa fa-cc-discover"></i></a></li>
                            <li><a href="#"><i class="fa fa-cc-amex"></i></a></li>
                        </ul>                       
                    </div>
                </div>
                <!-- /row -->
            </div>
            <!-- /container -->
        </div>
        <!-- /bottom footer -->
    </footer>
    <br><br>
</body>

<script src="{{ asset('electro-master/js/jquery.min.js') }}"></script>
<script src="{{ asset('electro-master/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('electro-master/js/slick.min.js') }}"></script>
<script src="{{ asset('electro-master/js/nouislider.min.js') }}"></script>
<script src="{{ asset('electro-master/js/jquery.zoom.min.js') }}"></script>
<script src="{{ asset('electro-master/js/main.js') }}"></script>

</html>
