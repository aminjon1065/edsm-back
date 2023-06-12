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

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::post('/send', [\App\Http\Controllers\DocumentController::class, 'store']);
    Route::post('document/{id}', [\App\Http\Controllers\DocumentController::class, 'update']);
    Route::get('/checkAuth', [\App\Http\Controllers\Auth\LoginController::class, 'checkAuth']);
    Route::post('/inbox', [\App\Http\Controllers\MailController::class, 'getInboxMails']);
    Route::get('/inbox/{uuid}', [\App\Http\Controllers\MailController::class, 'showMail']);
    Route::get("/documents", [\App\Http\Controllers\DocumentController::class, 'index']);
    Route::resource('users', 'App\Http\Controllers\UsersListController');
    Route::post('/showed/{id}', [\App\Http\Controllers\OpenedMailController::class, 'showed']);
    Route::post('/redirect-mail', [\App\Http\Controllers\MailController::class, 'redirectMail']);
//    Route::post('/search', [\App\Http\Controllers\MailController::class, 'search']);
});
