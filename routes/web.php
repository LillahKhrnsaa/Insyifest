<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormEksternalController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/form/{slug}', [FormEksternalController::class, 'show'])->name('form.show');
Route::post('/form/{slug}', [FormEksternalController::class, 'submit'])->name('form.submit');

