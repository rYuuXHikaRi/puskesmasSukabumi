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
Route::get('/user/show', [UserController::class, 'index']); //add -> middleware('auth:api') to use with authenticate user, but will throw 500 if token not valid
Route::post('/user/register', [UserController::class, 'register']);


Route::get('/medicine/unitmeasurement/show', [MedicineUnitMeasurenmentController::class, 'index']);
Route::post('/medicine/unitmeasurement/register', [MedicineUnitMeasurenmentController::class, 'store']);
Route::put('/medicine/unitmeasurement/edit/{id}', [MedicineUnitMeasurenmentController::class, 'update']);

Route::get('/medicine/storage/show', [MedicineStorageController::class, 'index']);
Route::post('/medicine/storage/register', [MedicineStorageController::class, 'store']);
Route::put('/medicine/storage/edit/{id}', [MedicineStorageController::class, 'update']);


