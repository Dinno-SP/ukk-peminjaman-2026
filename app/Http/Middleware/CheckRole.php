<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Cek apakah user sudah login & punya role yang sesuai
        if (!Auth::check() || !in_array(Auth::user()->role, $roles)) {
            // Kalau tidak boleh, tendang kembali
            abort(403, 'Akses Ditolak: Anda tidak punya izin masuk sini.');
        }

        return $next($request);
    }
}