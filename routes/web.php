<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShowProductController;
use App\Http\Controllers\OrderController;

Route::get('/', [ShowProductController::class, 'carusel'])->name('welcome');
Route::get('/producto/{id}/{slug?}', [ShowProductController::class, 'productodetalle'])->name('producto.productodetalle');
Route::get('/categoria/{id}/{slug?}', [ShowProductController::class, 'categoriatodo'])->name('categoria.categoriatodo');
Route::get('/marca/{id}/{slug?}', [ShowProductController::class, 'marcatodo'])->name('marca.marcatodo');
Route::get('/searchproducto', [ShowProductController::class, 'searchNombreProducto'])->name('search.searchProducto');
Route::post('/ordersearch', [OrderController::class, 'ordersearch'])->name('order.search');
Route::get('/comprar/{id}/{slug?}', [OrderController::class, 'ordershow'])->name('order.show');
Route::post('/comprar', [OrderController::class, 'store'])->name('order.store');
Route::get('/ordenes', [OrderController::class, 'searchOrden'])->name('searchOrden');
Route::post('/carritocomprar', [OrderController::class, 'carritoshow'])->name('carrito.show');


