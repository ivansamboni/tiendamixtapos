<?php
use App\Actions\Fortify\ResetUserPassword;
use App\Http\Controllers\Api\Productos\CategoriaController;
use App\Http\Controllers\Api\Productos\MarcaController;
use App\Http\Controllers\Api\Productos\ProductoController;
use App\Http\Controllers\Api\Productos\ProveedorController;
use App\Http\Controllers\Api\Productos\SearchController;
use Laravel\Fortify\Http\Controllers\PasswordResetLinkController;
use Laravel\Fortify\Http\Controllers\ResetPasswordController;
use Laravel\Fortify\Http\Controllers\NewPasswordController;
use App\Http\Controllers\Api\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::put('updateprofile', [AuthController::class, 'updateprofile']);
    Route::get('datauser', [AuthController::class, 'datauser']);

    //productos
    Route::namespace('App\Http\Controllers\Api\Productos')->group(function () {
        Route::apiResource('categorias', CategoriaController::class);
        Route::get('categoriaslist', [CategoriaController::class, 'categorias']);
        Route::apiResource('marcas', MarcaController::class);
        Route::get('marcaslist', [MarcaController::class, 'marcas']);
        Route::apiResource('proveedores', ProveedorController::class);
        Route::get('proveedoreslist', [ProveedorController::class, 'proveedores']);
        Route::apiResource('productos', ProductoController::class);
        Route::post('productos-loadimg', [ProductoController::class, 'loadimg']);
        Route::get('searchnomproducto/{nombre}', [SearchController::class, 'searchNombreProducto']);
        Route::get('searchcodigoproducto/{codigo_barras}', [SearchController::class, 'searcCodigoBarrasProducto']);
        Route::get('searchCodigoPaginate/{codigo_barras}', [SearchController::class, 'searchCodigoPaginate']);
    });

});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email')
    ->middleware('guest');

Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
    ->middleware('guest')
    ->name('password.reset');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.update');
