<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\AjusteController;
use App\Http\Controllers\SaleController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/ticket/{uuid}', [SaleController::class, 'generarTicket']);
Route::get('/facturapdf/{uuid}', [SaleController::class, 'facturaPDF']);
Route::get('/comprapdf/{id}', [PurchaseController::class, 'compraPDF']);
Route::get('/ajustepdf/{id}', [AjusteController::class, 'ajustePDF']);

