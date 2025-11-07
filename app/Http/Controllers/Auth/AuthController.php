<?php


namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Menampilkan form registrasi
    public function showRegistrationForm()
    {
        return view('auth.register'); // Pastikan Anda punya file ini
    }

    // Handle proses registrasi
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        // Simpan user ke database
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user' // <-- TAMBAHAN: Tentukan role secara eksplisit
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login untuk melanjutkan.');
    }

    // Menampilkan form login
    public function showLoginForm()
    {
        return view('auth.login'); // Pastikan Anda punya file ini
    }

    // Handle proses login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Coba login (menggunakan guard 'web' standar)
        if (Auth::attempt($credentials)) {
            
            // PERBAIKAN 2: Pengecekan Role (Keamanan)
            $user = Auth::user();

            // Hanya izinkan 'user' yang login di sini
            if ($user->role === 'user') {
                $request->session()->regenerate();
                // Arahkan ke beranda setelah login sukses
                return redirect()->intended('/');
            } else {
                // Jika 'admin' atau 'kasir' mencoba login, usir mereka
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun ini bukan akun pengguna. Silakan login di halaman admin.',
                ])->onlyInput('email');
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    // Handle proses logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}