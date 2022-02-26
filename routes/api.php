<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\WalletController;
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

Route::middleware(['api'])->group(function () {
    Route::prefix('v1')->group(function () {
        Route::prefix('auth')->controller(AuthController::class)->group(function () {
            Route::post('register', 'register')->name('register');
            Route::post('login', 'login')->name('login');
            Route::post('logout', 'logout')->name('logout');
            Route::post('refresh', 'refresh')->name('refresh');
            Route::post('me', 'me')->name('me');
        });
        Route::prefix('wallet')->name('wallet.')->controller(WalletController::class)->group(function () {
            Route::post('enable', 'enable')->name('enable');
            Route::post('disable', 'disable')->name('disable');
            Route::post('create', 'create')->name('create');
        });
    });
});
