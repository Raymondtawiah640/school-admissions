<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdmissionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmailsController;
use App\Http\Controllers\ImageUploadController;
use App\Http\Controllers\PaystackController;

Route::prefix('auth')->group(function () {
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/profile', [UserController::class, 'getProfile']);
        Route::post('/change-password', [UserController::class, 'changePassword']);
    });
});


Route::post('admissions', [AdmissionController::class, 'store']); 

Route::middleware('auth:sanctum')->group(function () {
    
    Route::apiResource('admissions', AdmissionController::class)
        ->except(['store']); 

    Route::patch('admissions/{id}/status', [AdmissionController::class, 'updateStatus']);

});


Route::post('welcome-email', [EmailsController::class, 'welcomeEmail']);

Route::post('upload-image', [ImageUploadController::class, 'uploadImage']);

// Paystack API Routes
Route::post('initialize-payment', [PaystackController::class, 'initializePayment']);
Route::post('verify-payment', [PaystackController::class, 'verifyPayment']);
Route::get('transactions', [PaystackController::class, 'fetchTransactions']);