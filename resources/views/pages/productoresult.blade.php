@extends('layouts.layout')

@section('title', 'Página de Inicio')

@section('content')
    <br>
    <br>
    <p class="text-center"> {{ $totalresult }} Encontrados <i class="bi bi-search"></i></p>
   
    <div class="container">
        <div class="row justify-content-center">
            @foreach ($productos as $pro)
                <x-card-product :pro="$pro" />
            @endforeach
        </div>
    </div>



    <br>
    <br>
    <div class="d-flex justify-content-center my-4">
        {{ $productos->links('vendor.pagination.custom') }}
    </div>

@endsection
