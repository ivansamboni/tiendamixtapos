<?php

use App\Models\Producto;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\AjusteController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\Api\Productos\ProductoController;
use App\Exports\Sale_detailExport;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/ticket/{uuid}', [SaleController::class, 'generarTicket']);
Route::get('/facturapdf/{uuid}', [SaleController::class, 'facturaPDF']);
Route::get('/comprapdf/{id}', [PurchaseController::class, 'compraPDF']);
Route::get('/ajustepdf/{id}', [AjusteController::class, 'ajustePDF']);
Route::get('/enviarfactura/{uuid}', [SaleController::class, 'enviarFactura']);

Route::get('/exportar-productos', [ProductoController::class, 'exportProductos']);

Route::get('/test-mail', function() {

    try {

        Mail::raw('Mensaje de prueba', function($message) {

            $message->to('andreskevin843@gmail.com')

                    ->subject('Prueba de correo HostGator');

        });

        return 'Correo enviado según Laravel';

    } catch (\Exception $e) {

        return 'Error: ' . $e->getMessage();

    }

});

 