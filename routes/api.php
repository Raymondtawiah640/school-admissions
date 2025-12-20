<?php 

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdmissionController;


Route::post('/admissions', [AdmissionController::class, 'store']);
Route::get('/admissions', [AdmissionController::class, 'index']);

Route::patch('/admissions/{id}/status', [AdmissionController::class, 'updateStatus']);