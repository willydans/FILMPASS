<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP - FilmPass</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .otp-input { letter-spacing: 0.5em; text-align: center; font-size: 1.5rem; }
    </style>
</head>
<body class="bg-gray-900 text-white flex items-center justify-center min-h-screen p-4">
    
    <div class="bg-black bg-opacity-80 p-8 rounded-xl shadow-2xl w-full max-w-md border border-gray-800">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-red-600 mb-2">FilmPass</h1>
            <h2 class="text-xl font-semibold">Verifikasi Keamanan</h2>
            <p class="text-gray-400 text-sm mt-2">
                Kami telah mengirimkan 6 digit kode ke email Anda. Masukkan kode tersebut di bawah ini.
            </p>
        </div>

        @if($errors->any())
            <div class="bg-red-500/20 text-red-200 p-3 rounded mb-4 text-sm border border-red-500/50">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('otp.verify.post') }}" method="POST" class="space-y-6">
            @csrf
            
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Kode OTP</label>
                <input type="text" name="otp" maxlength="6" required autofocus
                    class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg py-3 px-4 focus:ring-2 focus:ring-red-600 focus:border-transparent otp-input"
                    placeholder="000000">
            </div>

            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-lg transition duration-200">
                Verifikasi & Masuk
            </button>
        </form>

        <div class="text-center mt-6">
            <p class="text-sm text-gray-500">Tidak menerima kode?</p>
            <form action="{{ route('login') }}" method="GET"> {{-- Kembali ke login untuk ulang --}}
                <button type="submit" class="text-blue-500 hover:underline text-sm mt-1">Kirim Ulang / Login Kembali</button>
            </form>
        </div>
    </div>

</body>
</html>