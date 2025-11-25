<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP - FilmPass</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        html { font-family: 'Inter', sans-serif; }
        .background-img {
            background-image: url('https://i.pinimg.com/1200x/3b/88/8a/3b888ae33caddd009ea0262a6dace304.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        .min-h-full-screen { min-height: 100vh; }
        .otp-input { 
            letter-spacing: 0.75em; 
            text-align: center; 
            font-size: 1.75rem; 
            font-weight: 700;
        }
    </style>
</head>
<body class="bg-gray-900 min-h-full-screen text-white">

    <!-- Background Overlay (Sama seperti Login) -->
    <div class="background-img absolute inset-0 -z-10">
        <div class="absolute inset-0 bg-black opacity-60"></div>
    </div>
    
    <div class="flex flex-col items-center justify-center min-h-full-screen py-10 px-4">
        
        <!-- Logo -->
        <div class="mb-8">
             <a href="/" class="text-4xl font-extrabold text-white">
                <span class="text-red-600">Film</span>Pass
            </a>
        </div>

        <div class="bg-black bg-opacity-80 p-8 sm:p-12 rounded-lg w-full max-w-md shadow-2xl border-t-4 border-red-600">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold mb-2">Verifikasi Keamanan</h2>
                <p class="text-gray-400 text-sm">
                    Masukkan 6 digit kode OTP yang telah dikirim ke email Anda.
                </p>
            </div>

            <!-- Error Message -->
            @if($errors->any())
                <div class="bg-red-500/20 border border-red-500 text-red-100 px-4 py-3 rounded relative mb-6 text-sm">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('otp.verify.post') }}" method="POST" class="space-y-6">
                @csrf
                
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2 text-center uppercase tracking-wider">Kode OTP</label>
                    <input type="text" name="otp" maxlength="6" required autofocus
                        class="w-full bg-[#333] border-none text-white text-center py-4 rounded-md focus:outline-none focus:ring-2 focus:ring-red-600 placeholder-gray-600 otp-input shadow-inner"
                        placeholder="••••••" autocomplete="one-time-code">
                </div>

                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-md transition duration-200 transform hover:scale-[1.02]">
                    Verifikasi & Masuk
                </button>
            </form>

            <div class="text-center mt-8 pt-6 border-t border-gray-700">
                <p class="text-sm text-gray-500 mb-2">Tidak menerima kode?</p>
                <form action="{{ route('login') }}" method="GET"> 
                    <button type="submit" class="text-blue-500 hover:text-blue-400 hover:underline text-sm font-medium transition">
                        &larr; Kembali ke Login untuk Kirim Ulang
                    </button>
                </form>
            </div>
        </div>
    </div>

</body>
</html>