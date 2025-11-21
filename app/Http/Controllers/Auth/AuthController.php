<?php

namespace App\Http\Controllers\Auth; // Namespace yang benar (sesuai folder)

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite; // Untuk Google Login
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function __construct()
    {
        // Middleware guest: user yang sudah login tidak boleh akses login/register
        // Kecuali logout
        $this->middleware('guest')->except('logout');
    }

    // === REGISTRASI ===

    public function showRegistrationForm()
    {
        return view('auth.register'); 
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        // Simpan user baru dengan role 'user'
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user' // <-- PENTING: Set role default
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    // === LOGIN BIASA ===

    public function showLoginForm()
    {
        return view('auth.login'); 
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Coba login
        if (Auth::attempt($credentials)) {
            
            $user = Auth::user();

            // KEAMANAN: Cek apakah role-nya 'user'
            if ($user->role === 'user') {
                $request->session()->regenerate();
                return redirect()->intended('/'); // Masuk ke homepage
            } else {
                // Jika admin/kasir mencoba login di sini, tolak
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun ini bukan akun pengguna biasa. Silakan login di halaman admin.',
                ])->onlyInput('email');
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    // === LOGOUT ===

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    // === GOOGLE LOGIN (SOCIALITE) ===

    /**
     * Redirect user ke halaman login Google
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle callback dari Google
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Cari user berdasarkan google_id atau email
            $user = User::where('google_id', $googleUser->id)
                        ->orWhere('email', $googleUser->email)
                        ->first();

            if ($user) {
                // User ditemukan
                
                // Update google_id jika belum ada
                if (!$user->google_id) {
                    $user->update(['google_id' => $googleUser->id]);
                }

                // Cek role (hanya user biasa boleh login via Google di sini)
                if ($user->role !== 'user') {
                    return redirect()->route('login')->withErrors(['email' => 'Akun ini terdaftar sebagai Admin/Kasir. Silakan login manual.']);
                }

                Auth::login($user);
                return redirect()->intended('/');
            
            } else {
                // User baru (Register via Google)
                $newUser = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'role' => 'user', // Set role default user
                    'password' => Hash::make(Str::random(24)) // Password acak
                ]);

                Auth::login($newUser);
                return redirect()->intended('/');
            }

        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['email' => 'Login dengan Google gagal, silakan coba lagi.']);
        }
    }
}