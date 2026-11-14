<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\RegisterController;

// Landing page: shown to everyone at the home address "/"
Route::get('/', function () {
    return view('landing-page');
})->name('landing');

// Pages for visitors who are NOT logged in
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

// Pages only for logged-in users
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
    Route::get('/main', [MainController::class, 'index'])->name('main');
});