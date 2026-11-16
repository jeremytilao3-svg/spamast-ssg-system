<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\InstituteController;
use App\Http\Controllers\Admin\ProgramController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\Officer\StudentController;
use App\Http\Controllers\ProfileController;
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

    // /main only redirects to the dashboard of the user's role
    Route::get('/main', [MainController::class, 'view'])->name('main');

    // Profile (every role can edit their own profile)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Admin area: URL /admin/..., route names admin....
Route::middleware(['auth', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');

    // Approve / deactivate an account (full name: admin.users.toggle)
    Route::patch('users/{user}/toggle', [AdminUserController::class, 'toggle'])->name('users.toggle');

    // List, create, edit and delete users (no "show" page)
    Route::resource('users', AdminUserController::class)->except('show');

    // Institutes and programs (no "show" page)
    Route::resource('institutes', InstituteController::class)->except('show');
    Route::resource('programs', ProgramController::class)->except('show');
});

// Officer area: URL /officer/..., route names officer....
Route::middleware(['auth', 'role:Officer'])->prefix('officer')->name('officer.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'officer'])->name('dashboard');

    // Manage students (no "show" page)
    Route::resource('students', StudentController::class)->except('show');
});

// Adviser area: URL /adviser/dashboard, route name adviser.dashboard
Route::middleware(['auth', 'role:Adviser'])->prefix('adviser')->name('adviser.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'adviser'])->name('dashboard');
});

// Student area: URL /student/dashboard, route name student.dashboard
Route::middleware(['auth', 'role:Student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'student'])->name('dashboard');
});
// Events: Officer, Adviser and Student can view them
Route::middleware(['auth', 'role:Officer,Adviser,Student'])->group(function () {
    Route::get('/events', [\App\Http\Controllers\EventController::class, 'index'])->name('events.index');
    Route::get('/events/{event}', [\App\Http\Controllers\EventController::class, 'show'])->name('events.show');
});

// Events: only Officers can create, edit and delete them
Route::middleware(['auth', 'role:Officer'])->prefix('officer')->name('officer.')->group(function () {
    Route::resource('events', \App\Http\Controllers\Officer\EventController::class)->except(['index', 'show']);
});

// Officers open and close check-in / check-out of manual events
Route::middleware(['auth', 'role:Officer'])->prefix('officer')->name('officer.')->group(function () {
    Route::patch('/sessions/{session}/toggle', [\App\Http\Controllers\Officer\EventSessionController::class, 'toggle'])->name('sessions.toggle');
});

// Students scan in and out of a session
Route::middleware(['auth', 'role:Student'])->group(function () {
    Route::post('/sessions/{session}/check-in', [\App\Http\Controllers\Student\AttendanceController::class, 'checkIn'])->name('attendance.check-in');
    Route::post('/sessions/{session}/check-out', [\App\Http\Controllers\Student\AttendanceController::class, 'checkOut'])->name('attendance.check-out');
});

// Attendance list: Officers and Advisers can view it
Route::middleware(['auth', 'role:Officer,Adviser'])->group(function () {
    Route::get('/events/{event}/attendance', [\App\Http\Controllers\AttendanceController::class, 'index'])->name('attendance.index');
});

// Attendance changes: Officers only
Route::middleware(['auth', 'role:Officer'])->group(function () {
    Route::post('/events/{event}/attendance', [\App\Http\Controllers\Officer\AttendanceController::class, 'store'])->name('attendance.store');
    Route::put('/attendance/{attendance}', [\App\Http\Controllers\Officer\AttendanceController::class, 'update'])->name('attendance.update');
    Route::delete('/attendance/{attendance}', [\App\Http\Controllers\Officer\AttendanceController::class, 'destroy'])->name('attendance.destroy');
});
