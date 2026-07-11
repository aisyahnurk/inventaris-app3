<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Membatasi akses route berdasarkan role user yang sedang login.
     * Contoh pemakaian di routes/web.php: ->middleware(['auth', 'role:admin'])
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Cek: apakah user sudah login dan rolenya sesuai dengan yang disyaratkan route?
        if (Auth::check() && Auth::user()->role === $role) {
            return $next($request);
        }

        // Kalau role tidak sesuai, hentikan request dengan HTTP 403 Forbidden.
        abort(403, "Akses Dibatasi! Halaman ini khusus untuk role '{$role}'.");
    }
}