<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect('/login');
});

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister']);
    Route::get('/login', [AuthController::class, 'showLogin']);
});

Route::get('/dashboard', function () {
    return 'Welcome to SPAMAST SSG QR Attendance System!';
})->middleware('auth');
