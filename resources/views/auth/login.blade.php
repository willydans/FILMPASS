<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - FilmPass</title>
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
    </style>
</head>
<body class="bg-gray-900 min-h-full-screen text-white">

    <!-- Background Overlay -->
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

        <!-- Card Login -->
        <div class="bg-black bg-opacity-80 p-8 sm:p-12 rounded-lg w-full max-w-md shadow-2xl">
            <h1 class="text-3xl font-bold mb-6">Sign In</h1>

            <!-- Notifikasi Error -->
            @if ($errors->any())
                <div class="bg-red-500/20 border border-red-500 text-red-100 px-4 py-3 rounded relative mb-6 text-sm">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Notifikasi Sukses (Misal setelah register) -->
            @if(session('success'))
                <div class="bg-green-500/20 border border-green-500 text-green-100 px-4 py-3 rounded relative mb-6 text-sm">
                    {{ session('success') }}
                </div>
            @endif
            
            <!-- Form Login Manual -->
            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <input type="text" name="email" placeholder="Email or phone number" required value="{{ old('email') }}"
                        class="w-full bg-[#333] border-none text-white h-12 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-red-600 placeholder-gray-500">
                </div>
                <div>
                    <input type="password" name="password" placeholder="Password" required
                        class="w-full bg-[#333] border-none text-white h-12 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-red-600 placeholder-gray-500">
                </div>
                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-md transition duration-200 mt-2">
                    Sign In
                </button>
            </form>

            <!-- PEMISAH -->
            <div class="flex items-center my-6">
                <div class="flex-grow border-t border-gray-600"></div>
                <span class="mx-4 text-gray-400 text-sm">ATAU</span>
                <div class="flex-grow border-t border-gray-600"></div>
            </div>

            <!-- TOMBOL GOOGLE LOGIN -->
            <a href="{{ route('google.login') }}" class="w-full bg-white text-gray-800 font-bold py-3 rounded-md transition duration-200 flex items-center justify-center hover:bg-gray-100">
                <svg class="w-5 h-5 mr-3" viewBox="0 0 24 24">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                Masuk dengan Google
            </a>

            <!-- Footer Links -->
            <div class="flex justify-between items-center text-sm mt-6 mb-6 text-gray-400">
                <div class="flex items-center">
                    <input type="checkbox" id="remember" name="remember" class="form-checkbox bg-[#333] border-none rounded-sm text-gray-400 focus:ring-0" style="width: 1rem; height: 1rem;">
                    <label for="remember" class="ml-2 cursor-pointer hover:text-white">Remember me</label>
                </div>
                <a href="#" class="hover:underline hover:text-white">Need help?</a>
            </div>

            <div class="text-gray-400 mt-4 text-base">
                New to FilmPass? 
                <a href="{{ route('register') }}" class="text-white hover:underline font-semibold ml-1">Sign up now.</a>
            </div>
            
            <div class="text-xs text-gray-500 mt-4 pt-2">
                This page is protected by Google reCAPTCHA to ensure you're not a bot. 
                <a href="#" class="text-blue-500 hover:underline">Learn more.</a>
            </div>
        </div>
    </div>

</body>
</html>