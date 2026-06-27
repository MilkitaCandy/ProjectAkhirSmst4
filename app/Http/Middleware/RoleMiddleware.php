<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        // Cek apakah user sudah login dan role-nya sesuai dengan yang diminta di route
        if (auth()->check() && auth()->user()->role == $role) {
            return $next($request);
        }

        // Jika role tidak sesuai, tampilkan Error 403 Forbidden sesuai syarat laporan
        abort(403, '403 FORBIDDEN: Anda tidak memiliki hak akses ke halaman/fitur ini.');
    }
}