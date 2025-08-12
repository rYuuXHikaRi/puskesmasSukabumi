<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('/index', [UserController::class, 'index'])->middleware('auth:api');
Route::post('/register', [UserController::class, 'register']);
Route::post('/auth', [UserController::class, 'authenticate']);
Route::post('/deauth', [UserController::class, 'deauth']);
