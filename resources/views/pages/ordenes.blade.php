@extends('layouts.layout')

@section('title', 'Página de Inicio')

@section('content')
    <br>
    <div class="container" style="max-width: 30%">
        <form class="d-flex" role="search" action="{{ route('order.search') }}" method="POST">
            @csrf
            <input class="form-control me-2 search-input" type="search" name="id" placeholder="Ingrese numero de orden"
                value="{{ request('id') }}" aria-label="Search" required>
            <button class="btn btn-search" type="submit">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>


@endsection
