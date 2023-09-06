<?php

use App\Http\Controllers\Api\authController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// SignUp First API
Route::post('register', [authController::class, 'register'])->name('register');

// GetWorld First API
Route::get('getWorld', [authController::class, 'getWorld'])->name('getWorld');

// The Mobile/Email OTP Verification API 
Route::post('signupOTPVerify', [authController::class, 'signupOTPVerify'])->name('signupOTPVerify');
