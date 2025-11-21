<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response; // <-- Seringkali diperlukan, kita tambahkan

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response // <-- Tambahkan type-hint Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            // 1. Cek apakah user sudah login (menggunakan guard 'web' standar)
            if (Auth::guard($guard)->check()) {
                
                // --- INI PERBAIKAN UTAMANYA ---
                // 2. Jika sudah login, ambil data user
                $user = Auth::user();

                // 3. Cek rolenya
                if ($user->role == 'admin' || $user->role == 'kasir') {
                    
                    // 4. Jika dia admin/kasir, tendang ke dashboard ADMIN
                    return redirect()->route('admin.dashboard');

                } else {
                    
                    // 5. Jika dia user biasa, tendang ke dashboard USER
                    // (Asumsi rute dashboard user Anda bernama 'dashboard')
                    return redirect()->route('dashboard'); 
                }
                // -------------------------
            }
        }

        // 6. Jika belum login, izinkan lanjut (misal: ke halaman login)
        return $next($request);
    }
}