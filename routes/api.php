<?php

use App\Http\Controllers\Api\MedicineStorageController;
use App\Http\Controllers\Api\MedicineUnitMeasurenmentController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/auth', [UserController::class, 'authenticate']);
Route::post('/user/deauth', [UserController::class, 'deauth']);
Route::get('/user/show', [UserController::class, 'index'])->middleware('auth:api');
Route::post('/user/register', [UserController::class, 'register'])->middleware('auth:api');


Route::get('/medicine/unitmeasurement/show', [MedicineUnitMeasurenmentController::class, 'index'])->middleware('auth:api');
Route::post('/medicine/unitmeasurement/register', [MedicineUnitMeasurenmentController::class, 'store'])->middleware('auth:api');
Route::put('/medicine/unitmeasurement/edit/{id}', [MedicineUnitMeasurenmentController::class, 'update'])->middleware('auth:api');

Route::get('/medicine/storage/show', [MedicineStorageController::class, 'index'])->middleware('auth:api');
Route::post('/medicine/storage/register', [MedicineStorageController::class, 'store'])->middleware('auth:api');
Route::put('/medicine/storage/edit/{id}', [MedicineStorageController::class, 'update'])->middleware('auth:api');


