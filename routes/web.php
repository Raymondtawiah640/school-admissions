<?php
use App\Http\Controllers\EmailsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('welcome-email', [EmailsController::class, 'welcomeEmail']);
