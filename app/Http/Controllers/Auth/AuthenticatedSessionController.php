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
        $credentials = $request->validate([
            'phone' => ['required', 'string'],
            'password' => ['required'],
        ]);

        // --- INI UPDATE-NYA ---
        // Tambahkan syarat bahwa user harus 'active = 1'
        // Sesuai skema 'users' Anda
        $credentials['active'] = 1;
        // --- SELESAI UPDATE ---

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Logika redirect role kamu sudah benar
            $user = Auth::user();

            if ($user->hasRole('coach')) {
                return redirect()->route('coach.dashboard'); 
            }
            
            if ($user->hasRole('member')) {
                return redirect()->route('member.dashboard'); 
            }
            
            if ($user->hasRole('admin') || $user->hasRole('owner') || $user->hasRole('staff')) {
                return redirect('/admin');
            }

            return redirect()->intended(config('fortify.home'));
        }

        // --- INI UPDATE-NYA ---
        // Pesan error dibuat lebih umum untuk mencakup status tidak aktif
        return back()->withErrors([
            'phone' => 'Nomor telepon, password, atau status Anda tidak valid.',
        ])->onlyInput('phone');
    }

    /**
     * Destroy an authenticated session (logout).
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login'); 
    }
}