<?php

use Illuminate\Http\Request;
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


Route::prefix('v1')->group(function () {
    Route::post('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'register']);
    Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login']);
});

//Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
//    Route::post('document', [\App\Http\Controllers\DocumentController::class, 'store']);
//    Route::post('document/{id}', [\App\Http\Controllers\DocumentController::class, 'update']);
//});
