<?php

use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::controller(ApiAuthController::class)
    ->group(function () {
        Route::post('register', 'register');
        Route::post('login', 'login');
    });

Route::controller(TodoController::class)
    ->middleware('auth:api')
    ->prefix('todos')->group(function () {
        Route::get('/', 'all');
        Route::get('/{userId}', 'allByUser');
        Route::delete('/{id}', 'delete');
    });
