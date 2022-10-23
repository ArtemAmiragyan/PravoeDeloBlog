<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CommentController;
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
Route::middleware('guest')->group(function () {
    Route::post('login', LoginController::class);
    Route::post('register', RegisterController::class);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('articles/categories', [ArticleController::class, 'getCategories']);
    Route::apiResource('articles', ArticleController::class);
    Route::apiResource('comments', CommentController::class)->only('store');
    Route::get('user', [LoginController::class, 'user']);
    Route::post('logout', [LoginController::class, 'logout']);
});
