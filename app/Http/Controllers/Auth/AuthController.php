<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
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
            // Stateless wajib untuk Ngrok/Localhost
            $googleUser = Socialite::driver('google')->stateless()->user();
            
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

            // Lanjut ke OTP
            return $this->sendOtp($user);

        } catch (\Exception $e) {
            // Jika errornya parah (misal Google down), baru kita tampilkan
            return redirect()->route('login')->withErrors(['email' => 'Login Google gagal: ' . $e->getMessage()]);
        }
    }

    // === LOGIKA OTP ===

    private function sendOtp($user)
    {
        $otp = rand(100000, 999999);
        
        Cache::put('otp_' . $user->id, $otp, 300);
        session(['2fa:user_id' => $user->id]);

        // 1. Catat di Log (Backup Wajib untuk Development)
        Log::info("OTP MANUAL untuk {$user->email} adalah: {$otp}");

        // 2. Coba Kirim Email (Dengan Pengaman Try-Catch)
        try {
            Mail::to($user->email)->send(new OtpMail($otp));
        } catch (\Exception $e) {
            // JIKA GAGAL KIRIM EMAIL, JANGAN CRASH!
            // Cukup catat errornya di log, tapi biarkan user lanjut ke halaman verifikasi.
            Log::error("Gagal mengirim email SMTP: " . $e->getMessage());
        }

        // 3. Tetap arahkan ke halaman verifikasi meskipun email gagal
        // User bisa ambil kode dari Log file jika email tidak masuk
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