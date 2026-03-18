<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\OtpController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [OtpController::class, 'showEmailForm'])->name('otp.email.form');
Route::post('/login', [OtpController::class, 'sendOtp'])->name('otp.send');
Route::get('/verify', [OtpController::class, 'showVerifyForm'])->name('otp.verify.form');
Route::post('/verify', [OtpController::class, 'verifyOtp'])->name('otp.verify');