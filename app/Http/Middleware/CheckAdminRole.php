<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Cek apakah user sudah login DAN memiliki role 'admin' atau 'kasir'
        if (Auth::check() && (Auth::user()->role == 'admin' || Auth::user()->role == 'kasir')) {
            // 2. Jika ya, izinkan lanjut ke halaman yang dituju (misal: /admin/dashboard)
            return $next($request);
        }

        // 3. Jika tidak (dia user biasa atau tamu),
        // tendang dia kembali ke halaman login user biasa.
        return redirect()->route('login')->with('error', 'Anda tidak memiliki hak akses.');
    }
}