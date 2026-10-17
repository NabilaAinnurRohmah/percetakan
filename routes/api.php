<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PemilikMobileController;
use App\Http\Middleware\PemilikMobileMiddleware;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/mobile/login', [
    PemilikMobileController::class, 'login'
]);

Route::middleware([PemilikMobileMiddleware::class])
->prefix('mobile')
->group(function () {
    Route::get('/dashboard', [
        PemilikMobileController::class, 'dashboard'
    ]);
    Route::get('/pesanan', [
        PemilikMobileController::class, 'daftarPesanan'
    ]);
    Route::get('/pesanan/{id}', [
        PemilikMobileController::class, 'detailPesanan'
    ]);
});
