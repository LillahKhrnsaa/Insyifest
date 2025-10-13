<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\MemberRegistrationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormEksternalController;
use App\Http\Controllers\LandingController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::get('/form/{slug}', [FormEksternalController::class, 'show'])->name('form.show');
Route::post('/form/{slug}', [FormEksternalController::class, 'submit'])->name('form.submit');

// Rute untuk menampilkan form registrasi member
Route::get('/register/member', [MemberRegistrationController::class, 'create'])
    ->middleware('guest')
    ->name('member.register.store');
Route::post('/register/member', [MemberRegistrationController::class, 'store'])
    ->middleware('guest');

// Route untuk menampilkan halaman login (GET request)
Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->name('login'); // <--- ini penting!

Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');