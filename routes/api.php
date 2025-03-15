<?php
use App\Actions\Fortify\ResetUserPassword;
use App\Http\Controllers\Api\Productos\CategoryController;
use App\Http\Controllers\Api\Productos\MarcaController;
use App\Http\Controllers\Api\Productos\ProductoController;
use App\Http\Controllers\Api\Productos\SellerController;
use App\Http\Controllers\Api\Productos\StockController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\AjusteController;
use App\Http\Controllers\ImpuestoController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\Api\Productos\SearchController;
use App\Http\Middleware\AdminMiddleware;
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
    Route::post('productos-loadimg', [ProductoController::class, 'loadimg']);
    Route::get('searchnomproducto/{nombre}', [SearchController::class, 'searchNombreProducto']);
    Route::get('searchcodigoproducto/{codigo_barras}', [SearchController::class, 'searcCodigoBarrasProducto']);
    Route::get('searchCodigoPaginate/{codigo_barras}', [SearchController::class, 'searchCodigoPaginate']);
    //stock
    Route::apiResource('stock', StockController::class);    
    Route::post('/vender', [SaleController::class, 'store']);
    Route::get('/orderlist', [SaleController::class, 'index']);
    Route::get('/ordershow/{id}', [SaleController::class, 'show']);
    //clientes
    Route::apiResource('clientes', ClientController::class);
    Route::get('clientefinal/{cedula}', [ClientController::class, 'clienteFinalDatos']);
    //ventas
    Route::get('ventasfecha', [SaleController::class, 'ventasStats']);
});

//rutas accecibles para el rol ADMIN
Route::group(['middleware' => ['auth:sanctum', 'admin']], function () {
    Route::apiResource('categorias', CategoryController::class);
    Route::get('categoriaslist', [CategoryController::class, 'categorias']);
    Route::apiResource('marcas', MarcaController::class);
    Route::get('marcaslist', [MarcaController::class, 'marcas']);
    Route::apiResource('proveedores', SellerController::class);
    Route::get('proveedoreslist', [SellerController::class, 'proveedores']);
    Route::apiResource('productos', ProductoController::class);
    Route::apiResource('impuestos', ImpuestoController::class);
    Route::apiResource('purchase', PurchaseController::class);
    Route::post('comprafecha', [PurchaseController::class, 'filtroFecha']);
    Route::post('ventafecha', [SaleController::class, 'filtroFecha']);
    Route::post('ajustefecha', [AjusteController::class, 'filtroFecha']);
    Route::apiResource('ajuste', AjusteController::class);
    Route::apiResource('settings', SettingController::class);
    Route::apiResource('/users', AuthController::class); 
});
 
//Autenticación


Route::post('login', [AuthController::class, 'login']);
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email')
    ->middleware('guest');
Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
    ->middleware('guest')
    ->name('password.reset');
Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.update');
