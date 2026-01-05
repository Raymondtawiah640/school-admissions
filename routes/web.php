<?php
use App\Http\Controllers\EmailsController;
use App\Http\Controllers\PaystackController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('welcome-email', [EmailsController::class, 'welcomeEmail']);

// Paystack Routes
Route::get('/paystack-payment', [PaystackController::class, 'showPaymentForm']);
Route::post('/initialize-payment', [PaystackController::class, 'initializePayment']);
Route::get('/verify-payment', [PaystackController::class, 'verifyPayment']);
