<?php

// PASTIKAN NAMESPACE-NYA BENAR
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // <-- Ini sudah benar


class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        // Pastikan Anda punya file ini: resources/views/admin/auth/login.blade.php
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. PERBAIKAN: Gunakan Auth::attempt() standar (guard 'web')
        if (Auth::attempt($credentials)) {
            
            // 3. Ambil user yang baru saja login
            $user = Auth::user(); 

            // 4. Periksa rolenya
            if ($user->role === 'admin') { 
                // Sukses! Dia adalah admin.
                $request->session()->regenerate();
                return redirect()->intended(route('admin.dashboard'));
            
            } else {
                // Dia BUKAN admin (misal: 'user' atau 'kasir' mencoba login)
                // Usir dia!
                Auth::logout(); // <-- Langsung logout lagi
                return back()->withErrors([
                    'email' => 'Anda tidak memiliki hak akses admin.',
                ])->onlyInput('email');
            }
        }

        // 5. Jika Auth::attempt gagal (email/password salah)
        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        // 6. PERBAIKAN: Gunakan Auth::logout() standar
        Auth::logout(); 
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Arahkan kembali ke halaman login admin
        return redirect()->route('admin.login');
    }
}