<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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
$api_version = config('api.api_version');
Route::group(["prefix" => "{$api_version}"], function() {
    Route::get('users',[UserController::class,'index']);
    Route::get('users/{user_id}',[UserController::class,'getUserById']);
    Route::post('users',[UserController::class,'create']);
    Route::put('users/{user_id}',[UserController::class,'update']);
    Route::patch('users/{user_id}',[UserController::class,'update']);
    Route::delete('users/{user_id}',[UserController::class,'delete']);
}); 
