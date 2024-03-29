<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\InteractionController;
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

Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);

Route::group(['middleware' => ['auth:sanctum']], function(){

    Route::get('/user',[AuthController::class, 'user']);
    Route::post('/logout',[AuthController::class, 'logout']);
    Route::apiResource('posts', PostController::class);
    Route::get('/posts/{id}/comments',[CommentController::class, 'index']);
    Route::post('/posts/{id}/comments',[CommentController::class, 'store']);
    Route::put('/comments/{id}',[CommentController::class, 'update']);
    Route::delete('/comments/{id}',[CommentController::class, 'destroy']);
    Route::post('/posts/{id}/{Interact}', [InteractionController::class, 'post']);
    Route::post('/comments/{id}/{Interact}', [InteractionController::class, 'comment']);

});
