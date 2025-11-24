<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log; // Tambahkan Log
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use App\Mail\OtpMail;

class AuthController extends Controller
{
    public function __construct()
    {
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

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user'
        ]);

        return $this->sendOtp($user);
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

        if (Auth::validate($credentials)) {
            $user = User::where('email', $request->email)->first();

            if ($user->role !== 'user') {
                return back()->withErrors(['email' => 'Akun ini bukan akun pengguna biasa.']);
            }

            return $this->sendOtp($user);
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    // === GOOGLE LOGIN ===
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            $user = User::where('google_id', $googleUser->id)
                        ->orWhere('email', $googleUser->email)
                        ->first();

            if ($user) {
                if (!$user->google_id) {
                    $user->update(['google_id' => $googleUser->id]);
                }
                if ($user->role !== 'user') {
                    return redirect()->route('login')->withErrors(['email' => 'Akun ini terdaftar sebagai Admin/Kasir.']);
                }
            } else {
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'role' => 'user',
                    'password' => Hash::make(Str::random(24))
                ]);
            }

            return $this->sendOtp($user);

        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['email' => 'Login dengan Google gagal, silakan coba lagi.']);
        }
    }

    // === LOGIKA OTP (MODIFIED) ===

    private function sendOtp($user)
    {
        $otp = rand(100000, 999999);
        
        // Simpan di Cache & Session
        Cache::put('otp_' . $user->id, $otp, 300);
        session(['2fa:user_id' => $user->id]);

        // LOG BACKUP: Catat OTP di storage/logs/laravel.log (Penting untuk testing)
        Log::info("OTP untuk {$user->email} adalah: {$otp}");

        try {
            Mail::to($user->email)->send(new OtpMail($otp));
        } catch (\Exception $e) {
            // MODIFIKASI: Jika gagal kirim email di komputer lokal, jangan error.
            // Tetap lanjut ke halaman verifikasi agar bisa input OTP dari log.
            Log::error("Gagal kirim email OTP: " . $e->getMessage());
            
            // Jika di server production, baru kita kembalikan error
            if (config('app.env') === 'production') {
                 return redirect()->route('login')->withErrors(['email' => 'Gagal mengirim email OTP. Cek konfigurasi mail.']);
            }
        }

        return redirect()->route('otp.verify');
    }

    public function showOtpForm()
    {
        if (!session()->has('2fa:user_id')) {
            return redirect()->route('login');
        }
        return view('auth.verify_otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric|digits:6',
        ]);

        $userId = session('2fa:user_id');

        if (!$userId) {
            return redirect()->route('login')->withErrors(['email' => 'Sesi verifikasi habis.']);
        }

        $cachedOtp = Cache::get('otp_' . $userId);

        if ($cachedOtp && $request->otp == $cachedOtp) {
            Auth::loginUsingId($userId);
            session()->forget('2fa:user_id');
            Cache::forget('otp_' . $userId);
            $request->session()->regenerate();

            return redirect()->intended('/'); 
        } else {
            return back()->withErrors(['otp' => 'Kode OTP salah atau sudah kadaluarsa.']);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}