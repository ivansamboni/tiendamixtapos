@extends('layouts.layout')

@section('title', 'Página de Inicio')

@section('content')
    <br>
    <br>
    <h3 class="text-center">{{ $categoria->nombre ?? '' }}</h3>
    <br>
   
    <div class="container">
        <div class="row justify-content-center">
            @foreach ($productos as $pro)
                <x-card-product :pro="$pro" />
            @endforeach
        </div>
    </div>
        <br>
        <br>
        {{ $productos->links() }}

    @endsection
