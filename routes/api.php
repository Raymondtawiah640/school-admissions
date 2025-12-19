<?php 

use App\Http\Controllers\AdmissionController;

Route::post('/admissions', [AdmissionController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/admissions', [AdmissionController::class, 'index']);
    Route::patch('/admissions/{id}/status', [AdmissionController::class, 'updateStatus']);
}); 