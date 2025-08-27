<?php

use App\Http\Controllers\Api\EmergencyKitsController;
use App\Http\Controllers\Api\MedicineController;
use App\Http\Controllers\Api\MedicineStorageController;
use App\Http\Controllers\Api\MedicineUnitMeasurenmentController;
use App\Http\Controllers\Api\PatientController;
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
Route::post('/user/check', [UserController::class, 'checkToken']);


Route::get('/medicine/unitmeasurement/show', [MedicineUnitMeasurenmentController::class, 'index']);
Route::post('/medicine/unitmeasurement/register', [MedicineUnitMeasurenmentController::class, 'store']);
Route::put('/medicine/unitmeasurement/edit/{id}', [MedicineUnitMeasurenmentController::class, 'update']);

Route::get('/medicine/storage/show', [MedicineStorageController::class, 'index']);
Route::post('/medicine/storage/register', [MedicineStorageController::class, 'store']);
Route::put('/medicine/storage/edit/{id}', [MedicineStorageController::class, 'update']);
Route::get('/medicine/storage/stockView', [MedicineStorageController::class, 'medicineStockIndex']);
Route::post('/medicine/storage/stockAdd', [MedicineStorageController::class, 'medicineStockStore']);
Route::put('/medicine/storage/stockUpdate', [MedicineStorageController::class, 'medicineStockUpdate']);

Route::get('/medicine/show', [MedicineController::class, 'index']);
Route::post('/medicine/register', [MedicineController::class, 'store']);
Route::put('/medicine/edit/{id}', [MedicineController::class, 'update']);
Route::post('/medicine/createRetrieval', [MedicineController::class, 'createRetrievalMedicine']);


Route::get('/patient/show', [PatientController::class, 'index']);
Route::post('/patient/register', [PatientController::class, 'store']);
Route::put('/patient/edit/{id}', [PatientController::class, 'update']);

Route::get('/medkit/show', [EmergencyKitsController::class, 'index']);
Route::post('/medkit/register', [EmergencyKitsController::class, 'store']);
Route::put('/medkit/edit/{id}', [EmergencyKitsController::class, 'update']);
Route::get('/medkit/stockView', [EmergencyKitsController::class, 'stockIndex']);
Route::post('/medkit/stockAdd', [EmergencyKitsController::class, 'stockStore']);
Route::put('/medkit/stockUpdate', [EmergencyKitsController::class, 'stockUpdate']);
Route::put('/medkit/getMedicine', [EmergencyKitsController::class, 'getMedicine']);



