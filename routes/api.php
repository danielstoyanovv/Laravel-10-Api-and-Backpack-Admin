<?php

use App\Http\Controllers\Api\ProfilesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Api\PostsController;
use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\Api\ApiTokensController;

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

Route::apiResource('users', UsersController::class);
Route::post('/token',[ApiTokensController::class, 'token']);
Route::post('/login',[AuthenticationController::class, 'login']);
Route::post('/logout',[AuthenticationController::class, 'logout']);
Route::group(['middleware' => 'auth:api'], function(){
    Route::post('/activate-user', [UsersController::class, 'activateUser']);
    Route::post('/token-update', [UsersController::class, 'tokenUpdate']);
    Route::apiResource('posts', PostsController::class);
    Route::post('/post-like', [PostsController::class, 'postLike']);
    Route::post('/post-unlike', [PostsController::class, 'postUnLike']);
    Route::post('/deleted-at-clear', [PostsController::class, 'deletedAtClear']);
    Route::post('/user-update', [UsersController::class, 'userUpdate']);
    Route::post('/profiles', [ProfilesController::class, 'index']);
});
