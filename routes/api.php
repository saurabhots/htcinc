<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\LoginController;

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
Route::post('/v1/login',[LoginController::class,'login']);
Route::group(['prefix' => "{$api_version}",'middleware' => 'auth:sanctum'],function () {
    Route::get('users',[UserController::class,'index']);
    Route::get('users/{user_id}',[UserController::class,'getUserById']);
    Route::post('users',[UserController::class,'create']);
    Route::put('users/{user_id}',[UserController::class,'update']);
    Route::patch('users/{user_id}',[UserController::class,'update']);
    Route::delete('users/{user_id}',[UserController::class,'delete']);

    Route::get('project', [ProjectController::class,'index']);
    Route::get('project/{project_id}', [ProjectController::class,'getProjectById']);
    Route::post('project', [ProjectController::class,'create']);
    Route::put('project/{project_id}', [ProjectController::class,'update']);
    Route::delete('project/{project_id}', [ProjectController::class,'delete']);

    Route::get('task', [TaskController::class,'index']);
    Route::get('task/{id}', [TaskController::class,'getTaskById']);
    Route::post('task', [TaskController::class,'create']);
    Route::put('task/{id}', [TaskController::class,'update']);
    Route::delete('task/{id}', [TaskController::class,'delete']);
    Route::get('users-teams', [TaskController::class,'getTeams']);
    Route::post('assign-task', [TaskController::class,'assignTask']);
    Route::post('update-task-status', [TaskController::class,'updateStatus']);
}); 
