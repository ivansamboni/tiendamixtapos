<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShowProductController;

Route::get('/', [ShowProductController::class, 'carusel'])->name('welcome');
Route::get('/producto/{id}', [ShowProductController::class, 'productodetalle'])->name('producto.productodetalle');
Route::get('/categoria/{id}', [ShowProductController::class, 'categoriatodo'])->name('categoria.categoriatodo');
Route::get('/marca/{id}', [ShowProductController::class, 'marcatodo'])->name('marca.marcatodo');
