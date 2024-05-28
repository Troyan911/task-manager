<?php

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

Route::post('auth', [\App\Http\Controllers\Api\AuthController::class, 'auth'])->name('auth');

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('tasks', \App\Http\Controllers\Api\TaskController::class);
    Route::patch('tasks/{task}/done', [\App\Http\Controllers\Api\TaskController::class, 'done']);
});
