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


Route::post('signup', [\App\Http\Controllers\API\AuthController::class, 'signup']);
Route::post('/login', [\App\Http\Controllers\API\AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('logout', [\App\Http\Controllers\API\AuthController::class, 'logout']);
    // Route::get('persona', [\App\Http\Controllers\API\PersonaController::class, 'index']);
    Route::apiResource('user', \App\Http\Controllers\API\PersonaController::class);
});

// Route::apiResource('categoria', \App\Http\Controllers\API\CategoriaController::class);
Route::get('categoria', [\App\Http\Controllers\API\CategoriaController::class, 'index']);
// Route::post('categoria', [\App\Http\Controllers\API\CategoriaController::class, 'store']);

Route::apiResource('flores', \App\Http\Controllers\API\FloresController::class);
Route::get('flores/categoria/{id}',  [\App\Http\Controllers\API\FloresController::class, 'byCategory']);
Route::get('flores/categoria-especial/{id}',  [\App\Http\Controllers\API\FloresController::class, 'bySpecialCategory']);
