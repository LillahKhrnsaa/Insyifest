<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Show the login form.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. UBAH VALIDASI DARI 'email' MENJADI 'phone'
        $credentials = $request->validate([
            'phone' => ['required', 'string'], // <-- DIUBAH DARI 'email'
            'password' => ['required'],
        ]);

        // Proses otentikasi (Auth::attempt) akan otomatis menggunakan 'phone'
        // karena $credentials sekarang berisi ['phone' => '...', 'password' => '...']
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Redirect semua user ke admin panel Filament
            return redirect('/admin');
        }

        // 2. UBAH PENANGANAN ERROR UNTUK 'phone'
        return back()->withErrors([
            'phone' => 'Nomor telepon atau password yang diberikan tidak valid.', // <-- DIUBAH
        ])->onlyInput('phone'); // <-- DIUBAH
    }

    /**
     * Destroy an authenticated session (logout).
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect ke route yang BERNAMA 'login'
        return redirect()->route('login'); 
    }
}