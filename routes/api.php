<?php 

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdmissionController;


Route::apiResource('admissions', AdmissionController::class)
->only(['index', 'store']);
Route::patch('/admissions/{id}/status', [AdmissionController::class, 'updateStatus']); 