<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;

// Landing page: shown to everyone at the home address "/"
Route::get('/', function () {
    return view('landing-page');
})->name('landing');

// Pages for visitors who are NOT logged in
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::get('/login', [LoginController::class, 'show'])->name('login');
});

// Kept from before: replaced later in step 3.4
Route::get('/dashboard', function () {
    return 'Welcome to SPAMAST SSG Attendance System!';
})->middleware('auth');