<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShowProductController;

Route::get('/', [ShowProductController::class, 'carusel'])->name('welcome');
Route::get('/producto/{id}/{slug?}', [ShowProductController::class, 'productodetalle'])->name('producto.productodetalle');
Route::get('/categoria/{id}/{slug?}', [ShowProductController::class, 'categoriatodo'])->name('categoria.categoriatodo');
Route::get('/marca/{id}/{slug?}', [ShowProductController::class, 'marcatodo'])->name('marca.marcatodo');
Route::get('/searchproducto', [ShowProductController::class, 'searchNombreProducto'])->name('search.searchProducto');


