@extends('layouts.layout')

@section('title', 'Página de Inicio')

@section('content')
<div id="carouselplublicidad" class="carousel slide carousel-fade" data-bs-ride="carousel">
    <div class="carousel-indicators">
      <button type="button" data-bs-target="#carouselplublicidad" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
      <button type="button" data-bs-target="#carouselplublicidad" data-bs-slide-to="1" aria-label="Slide 2"></button>
      <button type="button" data-bs-target="#carouselplublicidad" data-bs-slide-to="2" aria-label="Slide 3"></button>
    </div>
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="https://tiendacereza.com/cdn/shop/files/19-11-Banner-Principal-Desktop-Navidad-Disfraces_1343x500_crop_center.png" class="d-block w-100" alt="">        
      </div>
      <div class="carousel-item active">
        <img src="https://tiendacereza.com/cdn/shop/files/IMG_4428_1343x500_crop_center.png" class="d-block w-100" alt=""> 
      </div>
      <div class="carousel-item active">
        <img src="https://tiendacereza.com/cdn/shop/files/IMG_4428_1343x500_crop_center.png" class="d-block w-100" alt=""> 
      </div>           
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselplublicidad" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselplublicidad" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>
  
<br>
    <div id="productCarousel" class="carousel carousel-dark slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <h3 class="title text-center">NUEVOS PRODUCTOS</h3>
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
    <!-- SECTION -->
    <br>

    <div class="row">
        <img src="https://tiendacereza.com/cdn/shop/files/Banner_LANZAMIENTO_BW_PC_1343x500_crop_center.png" alt="">
    </div>
    <br>
    <div class="container">
        @foreach ($categorias->slice(0, 6) as $index => $cat)
            <h3 class="title"><i class="bi bi-tags"></i> {{ $cat->nombre }}</h3> <!-- Nombre de la categoría -->
            <br><br>
            <div id="productCarousel-{{ $index }}" class="carousel carousel-dark slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @forelse ($cat->producto->chunk(5) as $chunk)
                        <!-- Dividir productos en chunks de 5 -->
                        <div class="carousel-item @if ($loop->first) active @endif">
                            <div class="row justify-content-center">
                                @foreach ($chunk as $pro)
                                    <x-card-product :pro="$pro" />
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="carousel-item active">
                            <div class="row justify-content-center">
                                <p>Próximamente.</p>
                            </div>
                        </div>
                    @endforelse
                </div> <!-- Cierre de .carousel-inner -->

                <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel-{{ $index }}"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#productCarousel-{{ $index }}"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
            <br> <br>
        @endforeach
    </div>


    <div class="container">
        <h3 class="title"><i class="bi bi-tags"></i> TODAS LAS CATEGORÍAS</h3><br>
        <div class="row justify-content-center">
            @foreach ($productoall as $pro)
                <x-card-product :pro="$pro" />
            @endforeach
        </div>
    </div>



    <br><br><br>
    <div class="d-flex justify-content-center my-4">
        
        {{ $productoall->links('vendor.pagination.custom') }}
    </div>

@endsection
