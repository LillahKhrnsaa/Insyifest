<?php

use App\Http\Controllers\Auth\MemberRegistrationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormEksternalController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/form/{slug}', [FormEksternalController::class, 'show'])->name('form.show');
Route::post('/form/{slug}', [FormEksternalController::class, 'submit'])->name('form.submit');

// Rute untuk menampilkan form registrasi member
Route::get('/register/member', [MemberRegistrationController::class, 'create'])
    ->middleware('guest')
    ->name('member.register.store');
Route::post('/register/member', [MemberRegistrationController::class, 'store'])
    ->middleware('guest');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});