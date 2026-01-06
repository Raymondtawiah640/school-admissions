<?php
use App\Http\Controllers\EmailsController;
use App\Http\Controllers\PaystackController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageUploadController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('welcome-email', [EmailsController::class, 'welcomeEmail']);

Route::post('welcome-email', [EmailsController::class, 'welcomeEmail']);

Route::post('upload-image', [ImageUploadController::class, 'uploadImage']);


// Paystack API Routes
Route::post('initialize-payment', [PaystackController::class, 'initializePayment']);
Route::post('verify-payment', [PaystackController::class, 'verifyPayment']);
Route::get('transactions', [PaystackController::class, 'fetchTransactions']);