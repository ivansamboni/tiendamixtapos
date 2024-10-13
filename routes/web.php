<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShowProductController;

Route::get('/', [App\Http\Controllers\ShowProductController::class, 'carusel']);
