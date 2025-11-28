<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\MemberRegistrationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormEksternalController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\CoachDashboardController;
use App\Http\Controllers\AttendanceController;


// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::get('/form/{slug}', [FormEksternalController::class, 'show'])->name('form.show');
Route::post('/form/{slug}', [FormEksternalController::class, 'submit'])->name('form.submit');

// Rute untuk menampilkan form registrasi member
// Rute GET untuk MENAMPILKAN form, diberi nama 'create'
Route::get('/register/member', [MemberRegistrationController::class, 'create'])
    ->middleware('guest')
    ->name('member.register.create'); // <--- NAMA DIUBAH

// Rute POST untuk MENYIMPAN data, diberi nama 'store'
Route::post('/register/member', [MemberRegistrationController::class, 'store'])
    ->middleware('guest')
    ->name('member.register.store'); // <--- NAMA DITAMBAHKAN

// Route untuk menampilkan halaman login (GET request)
Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->name('login'); // <--- ini penting!

Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

Route::middleware(['auth', 'verified'])->group(function () {

    // Rute fallback default (jika ada user login tanpa role)
    Route::get('/dashboard', function () {
        return view('dashboard'); // <-- Pastikan view 'dashboard.blade.php' ada
    })->name('dashboard');

    // Rute Dashboard Coach (INI YANG KITA AKTIFKAN)
    Route::get('/coach/dashboard', [CoachDashboardController::class, 'index'])
        ->name('coach.dashboard');

    Route::post('/attendance/store', [AttendanceController::class, 'store'])
         ->name('attendance.store');
    
    Route::get('/api/raport/chart-data', [CoachDashboardController::class, 'getChartData'])
        ->name('api.raport.chart');

    Route::post('/api/raport/create', [CoachDashboardController::class, 'createRaport'])
        ->name('api.raport.create');

    // Update Raport
    Route::put('/api/raport/update/{id}', [CoachDashboardController::class, 'updateRaport'])
        ->name('api.raport.update');

    // Delete Raport
    Route::delete('/api/raport/delete/{id}', [CoachDashboardController::class, 'deleteRaport'])
        ->name('api.raport.delete');

    // Get Available Months
    Route::get('/api/raport/available-months', [CoachDashboardController::class, 'getAvailableMonths'])
        ->name('api.raport.months');

    // Get Coaches List
    Route::get('/api/raport/coaches', [CoachDashboardController::class, 'getCoachesList'])
        ->name('api.raport.coaches');
    
    // Rute Dashboard Member (sesuai rencana)
    Route::get('/member/dashboard', function() {
        // Nanti kamu bisa ganti ini ke controller-mu
        return view('member.dashboard'); // <-- Buat file 'resources/views/member/dashboard.blade.php'
    })->name('member.dashboard');
    
    // Rute untuk profile, dll. bisa ditambahkan di sini
    // Contoh: Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
});

// Route::middleware(['auth'])->group(function () {
//     Route::get('/coach-dashboard', [CoachDashboardController::class, 'index'])
//         ->name('coach.dashboard');
// });