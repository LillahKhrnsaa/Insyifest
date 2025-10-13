<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // Jika request BUKAN request API (expectsJson),
        // maka arahkan pengguna ke halaman login kustom kamu.
        // Jika ini adalah request API, biarkan null agar Laravel mengembalikan error 401.
        return $request->expectsJson() ? null : route('login');
    }
}