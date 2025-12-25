<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdmissionController;
use App\Http\Controllers\UserController;


Route::prefix('auth')->group(function () {
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);
});


Route::prefix('admissions')->group(function () {

    Route::post('/', [AdmissionController::class, 'store']);

    // Protected (auth required)
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/', [AdmissionController::class, 'index']);
        Route::patch('/{id}/status', [AdmissionController::class, 'updateStatus']);
    });

});
