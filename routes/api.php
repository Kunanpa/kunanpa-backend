<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

/**
 * Rutas de acceso para clientes
*/
Route::post('signup', [\App\Http\Controllers\API\AuthController::class, 'signup']);
Route::post('/login', [\App\Http\Controllers\API\AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    //Route::get('logout', [\App\Http\Controllers\API\AuthController::class, 'logout']);
    Route::apiResource('user', \App\Http\Controllers\API\PersonaController::class)->only('show');
    Route::apiResource('shopping', \App\Http\Controllers\API\ShoppingController::class)->only('store');
    Route::apiResource('wish-list', \App\Http\Controllers\API\WishListController::class)->only(['store', 'destroy', 'index']);
});
//TODO: REMOVIDO VALIDACION DE TOKEN TEMPORAL
Route::get('logout', [\App\Http\Controllers\API\AuthController::class, 'logout']);

// Route::apiResource('categoria', \App\Http\Controllers\API\CategoriaController::class);
Route::get('categoria', [\App\Http\Controllers\API\CategoriaController::class, 'index']);
// Route::post('categoria', [\App\Http\Controllers\API\CategoriaController::class, 'store']);

// Route::apiResource('flores', \App\Http\Controllers\API\FloresController::class)->except('store');
Route::apiResource('flores', \App\Http\Controllers\API\FloresController::class)->only(['index', 'show']);
Route::get('flores/categoria/{id}',  [\App\Http\Controllers\API\FloresController::class, 'byCategory']);
Route::get('flores/categoria-especial/{id}',  [\App\Http\Controllers\API\FloresController::class, 'bySpecialCategory']);




/**
 * Rutas de acceso para administradores
 */
// Route::get('categoria', [\App\Http\Controllers\API\CategoriaController::class, 'index']);

Route::post('admin/login', [\App\Http\Controllers\API\AuthController::class, 'loginAdmin']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('admin/logout', [\App\Http\Controllers\API\AuthController::class, 'logout']);
    Route::post('flores', [\App\Http\Controllers\API\FloresController::class, 'store']);
    Route::get('flores/store/{idStore}', [\App\Http\Controllers\API\FloresController::class, 'byStore']);
    Route::get('pedidos/{idStore}', [\App\Http\Controllers\API\ShoppingController::class, 'getOrders']);

    Route::get('detalles-pedido/{idOrder}', [\App\Http\Controllers\API\ShoppingController::class, 'ordersDetails']);
    Route::post('cambiar-estado', [\App\Http\Controllers\API\ShoppingController::class, 'changeStatus']);
});

/**
 * Rutas para el dashboard
*/
Route::get('ventas', [\App\Http\Controllers\API\DashboardController::class, 'ventas']);
Route::get('pedidos', [\App\Http\Controllers\API\DashboardController::class, 'pedidos']);


/**
 * Rutas de webhook para mercado pago
 */
Route::post('webhook', [\App\Http\Controllers\API\ShoppingController::class, 'webhookMP']);
