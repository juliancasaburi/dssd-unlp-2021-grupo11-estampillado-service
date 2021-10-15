<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EstampilladoController;

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

/* Auth */
Route::post('auth/login', [AuthController::class, 'login']);

/* JWT protected routes */
Route::group(['middleware' => ['apiJwt']], function () {
    /* Auth */
    Route::post('auth/logout', [AuthController::class, 'logout']);
    /* Estampillado */
    Route::post('estampillar', [EstampilladoController::class, 'estampillar']);
});