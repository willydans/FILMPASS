<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite; // Jangan lupa import Socialite

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 1. Cek kredensial (email & password)
        if (Auth::attempt($credentials)) {
            
            $user = Auth::user();

            // 2. Cek Role Admin
            if ($user->role === 'admin') {
                $request->session()->regenerate();
                return redirect()->intended(route('admin.dashboard'));
            } else {
                // Jika bukan admin, logout paksa
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun Anda tidak memiliki akses Admin.',
                ])->onlyInput('email');
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    /**
     * Redirect ke Google untuk Admin
     */
    public function redirectToGoogle()
    {
        // Menggunakan driver google, tapi kita bisa set redirect URL khusus admin jika perlu
        // Pastikan Anda sudah menambahkan route 'admin.google.callback' di web.php
        return Socialite::driver('google')
            // ->redirectUrl(route('admin.google.callback')) // Opsional: jika ingin URL callback beda
            ->redirect();
    }

    /**
     * Handle Callback dari Google
     */
    public function handleGoogleCallback()
    {
        try {
            // Ambil data user dari Google
            $googleUser = Socialite::driver('google')->user();
            
            // Cari user di database berdasarkan email
            $user = User::where('email', $googleUser->getEmail())->first();

            // Skenario 1: User tidak ditemukan
            if (!$user) {
                return redirect()->route('admin.login')
                    ->withErrors(['email' => 'Email ini tidak terdaftar dalam sistem.']);
            }

            // Skenario 2: User ada, tapi bukan Admin
            if ($user->role !== 'admin') {
                return redirect()->route('admin.login')
                    ->withErrors(['email' => 'Akun Google ini bukan akun Admin.']);
            }

            // Skenario 3: User ada & Admin (Sukses)
            Auth::login($user);
            request()->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'));

        } catch (\Exception $e) {
            return redirect()->route('admin.login')
                ->withErrors(['email' => 'Gagal login dengan Google. Silakan coba lagi.']);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}