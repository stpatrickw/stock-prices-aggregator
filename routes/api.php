<?php

use App\Http\Controllers\ApiStockController;
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

/* The routes use basic authentication */
Route::middleware(['auth.token', 'throttle:20,1'])->group(function () {
    Route::get('/stock/symbol/list', [ApiStockController::class, 'symbolListAction']);
    Route::get('/stock/latest-price/{symbol?}', [ApiStockController::class, 'latestPriceAction']);
    Route::get('/stock/price-changes/{symbol?}', [ApiStockController::class, 'priceChangesAction']);
});
