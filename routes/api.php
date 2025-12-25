<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdmissionController;
use App\Http\Controllers\UserController;


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/admissions', [AdmissionController::class, 'index']);
    Route::patch('/admissions/{id}/status', [AdmissionController::class, 'updateStatus']);
});

Route::post('/admissions', [AdmissionController::class, 'store']);

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);