<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\MemberRegistrationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormEksternalController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\CoachDashboardController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\MemberDashboardController;
use Livewire\Livewire;

Livewire::setUpdateRoute(function ($handle) {
    return Route::post('/livewire/update', $handle)
        ->middleware('web');
});

Livewire::setScriptRoute(function ($handle) {
    return Route::get('/livewire/livewire.js', $handle);
});

Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::get('/form/{slug}', [FormEksternalController::class, 'show'])->name('form.show');
Route::post('/form/{slug}', [FormEksternalController::class, 'submit'])->name('form.submit');

// Rute untuk menampilkan form registrasi member
Route::get('/register/member', [MemberRegistrationController::class, 'create'])
    ->middleware('guest')
    ->name('member.register.create');

Route::post('/register/member', [MemberRegistrationController::class, 'store'])
    ->middleware('guest')
    ->name('member.register.store');

// Route untuk menampilkan halaman login (GET request)
Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->name('login');

Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

Route::middleware(['auth', 'verified'])->group(function () {

    // Rute fallback default (jika ada user login tanpa role)
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Rute Dashboard Coach
    Route::get('/coach/dashboard', [CoachDashboardController::class, 'index'])
        ->name('coach.dashboard');

    Route::post('/attendance/store', [AttendanceController::class, 'store'])
         ->name('attendance.store');

    // [TAMBAHAN BARU] Update & Delete Absensi - Arahkan ke CoachDashboardController
    Route::put('/attendance/update/{id}', [CoachDashboardController::class, 'updateAttendance'])
        ->name('attendance.update');

    Route::delete('/attendance/delete/{id}', [CoachDashboardController::class, 'destroyAttendance'])
        ->name('attendance.destroy');
    
    // Coach Raport API Routes
    Route::get('/api/raport/chart-data', [CoachDashboardController::class, 'getChartData'])
        ->name('api.raport.chart');

    Route::post('/api/raport/create', [CoachDashboardController::class, 'createRaport'])
        ->name('api.raport.create');

    Route::put('/api/raport/update/{id}', [CoachDashboardController::class, 'updateRaport'])
        ->name('api.raport.update');

    Route::delete('/api/raport/delete/{id}', [CoachDashboardController::class, 'deleteRaport'])
        ->name('api.raport.delete');

    Route::get('/api/raport/available-months', [CoachDashboardController::class, 'getAvailableMonths'])
        ->name('api.raport.months');

    Route::get('/api/raport/coaches', [CoachDashboardController::class, 'getCoachesList'])
        ->name('api.raport.coaches');
    
    // ═══════════════════════════════════════════════════════════════
    // MEMBER DASHBOARD ROUTES - TAMBAHKAN INI
    // ═══════════════════════════════════════════════════════════════
    
    // Rute Dashboard Member dengan Controller
    Route::get('/member/dashboard', [MemberDashboardController::class, 'index'])
        ->name('member.dashboard');

    // Member Performance API Routes
    Route::get('/member/performance-data', [MemberDashboardController::class, 'getPerformanceData'])
        ->name('member.performance.data');

    Route::get('/member/attendance-history', [MemberDashboardController::class, 'getAttendanceHistory'])
        ->name('member.attendance.history');
});