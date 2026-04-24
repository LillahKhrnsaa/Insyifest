<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\MemberRegistrationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormEksternalController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\CoachDashboardController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\FormExternalController;
use App\Http\Controllers\MemberDashboardController;
use Livewire\Livewire;

Livewire::setUpdateRoute(function ($handle) {
    return Route::post('/livewire/update', $handle)
        ->middleware('web');
});

// Route::get('/debug-scheme', function () {
//     return [
//         'is_secure' => request()->isSecure(),
//         'scheme' => request()->getScheme(),  
//         'url' => request()->fullUrl(),
//     ];
// });


Route::get('/', [LandingController::class, 'index'])->name('landing');

// ═══════════════════════════════════════════════════════════════
// EXTERNAL FORM ROUTES (Public)
// ═══════════════════════════════════════════════════════════════

// Intelligent Route for /form/{slug} - Supports both RegistrationForm and FormEksternal
Route::get('/form/{slug}', function($slug) {
    if (\App\Models\RegistrationForm::where('slug', $slug)->exists()) {
        return app(\App\Http\Controllers\FormExternalController::class)->show($slug);
    }
    if (\App\Models\FormEksternal::where('slug', $slug)->exists()) {
        return app(\App\Http\Controllers\FormEksternalController::class)->show($slug);
    }
    abort(404);
})->name('form.external.show');

Route::post('/form/{slug}', function(\Illuminate\Http\Request $request, $slug) {
    if (\App\Models\RegistrationForm::where('slug', $slug)->exists()) {
        return app(\App\Http\Controllers\FormExternalController::class)->submit($request, $slug, app(\App\Services\Registration\RegistrationSubmissionService::class));
    }
    if (\App\Models\FormEksternal::where('slug', $slug)->exists()) {
        return app(\App\Http\Controllers\FormEksternalController::class)->submit($request, $slug);
    }
    abort(404);
})->name('form.external.submit');

// Route for FormEksternal (Indonesian/Simple)
Route::get('/f/{slug}', [FormEksternalController::class, 'show'])->name('form.eksternal.show');
Route::post('/f/{slug}', [FormEksternalController::class, 'submit'])->name('form.submit');

// Rute untuk menampilkan form registrasi member default
Route::get('/register/member', [MemberRegistrationController::class, 'create'])

    ->middleware('guest')
    ->name('member.register.create');

Route::get('/pendaftar', [MemberRegistrationController::class, 'pendaftar'])
    ->name('pendaftar');

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

    // Member Management Routes for Coach
    Route::post('/coach/member/store', [CoachDashboardController::class, 'storeMember'])
        ->name('coach.member.store');
    Route::put('/coach/member/update/{id}', [CoachDashboardController::class, 'updateMember'])
        ->name('coach.member.update');
    Route::delete('/coach/member/delete/{id}', [CoachDashboardController::class, 'deleteMember'])
        ->name('coach.member.delete');
    
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
    
    // Coach Physical Test API Routes
    Route::get('/api/physical/data', [CoachDashboardController::class, 'getPhysicalData'])
        ->name('api.physical.data');
    Route::post('/api/physical/store', [CoachDashboardController::class, 'storePhysicalTest'])
        ->name('api.physical.store');
    Route::put('/api/physical/update/{id}', [CoachDashboardController::class, 'updatePhysicalTest'])
        ->name('api.physical.update');
    Route::delete('/api/physical/delete/{id}', [CoachDashboardController::class, 'deletePhysicalTest'])
        ->name('api.physical.delete');
    Route::get('/api/physical/variables', [CoachDashboardController::class, 'getPhysicalVariables'])
        ->name('api.physical.variables');
    Route::post('/api/physical/variables/store', [CoachDashboardController::class, 'storePhysicalVariables'])
        ->name('api.physical.variables.store');
    
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

    Route::post('/member/status/toggle', [MemberDashboardController::class, 'toggleStatus'])
        ->name('member.toggle-status');

    Route::get('/api/member/physical-data', [MemberDashboardController::class, 'getPhysicalData'])
        ->name('api.member.physical.data');
});

// Catch-all route for slugs at the root level
Route::get('/{slug}', function($slug) {
    // Try FormEksternal first
    $form1 = \App\Models\FormEksternal::where('slug', $slug)->first();
    if ($form1) {
        return app(\App\Http\Controllers\FormEksternalController::class)->show($slug);
    }
    
    // Try RegistrationForm next
    $form2 = \App\Models\RegistrationForm::where('slug', $slug)->first();
    if ($form2) {
        return app(\App\Http\Controllers\FormExternalController::class)->show($slug);
    }
    
    abort(404);
})->name('form.slug.catchall');