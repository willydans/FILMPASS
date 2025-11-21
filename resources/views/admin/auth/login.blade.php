<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Admin - FilmPass</title>
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
                <span class="text-red-600">Film</span>Pass <span class="text-sm font-normal text-gray-300">Admin</span>
            </a>
        </div>

        <!-- Card Login -->
        <div class="bg-black bg-opacity-80 p-8 sm:p-12 rounded-lg w-full max-w-md shadow-2xl border-t-4 border-red-600">
            <h1 class="text-3xl font-bold mb-6 text-center">Admin Login</h1>

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
            
            <!-- Form Login Manual -->
            <form action="{{ route('admin.login') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="email" class="sr-only">Email</label>
                    <input type="text" id="email" name="email" placeholder="Admin Email" required value="{{ old('email') }}"
                        class="w-full bg-[#333] border-none text-white h-12 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-red-600 placeholder-gray-500 transition-all">
                </div>
                <div>
                    <label for="password" class="sr-only">Password</label>
                    <input type="password" id="password" name="password" placeholder="Password" required
                        class="w-full bg-[#333] border-none text-white h-12 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-red-600 placeholder-gray-500 transition-all">
                </div>
                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-md transition duration-200 mt-2 transform hover:scale-[1.02]">
                    Sign In to Dashboard
                </button>
            </form>

            <!-- PEMISAH -->
            <div class="flex items-center my-6">
                <div class="flex-grow border-t border-gray-600"></div>
                <span class="mx-4 text-gray-400 text-sm">ATAU</span>
                <div class="flex-grow border-t border-gray-600"></div>
            </div>

            <!-- TOMBOL GOOGLE LOGIN (ADMIN) -->
            {{-- Pastikan route 'admin.google.login' sudah didefinisikan di routes/web.php --}}
            <a href="{{ route('google.login') }}" class="w-full bg-white text-gray-800 font-bold py-3 rounded-md transition duration-200 flex items-center justify-center hover:bg-gray-100 transform hover:scale-[1.02]">
                <svg class="w-5 h-5 mr-3" viewBox="0 0 24 24">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                Masuk dengan Google
            </a>

            <div class="mt-8 text-center text-gray-500 text-xs">
                <p>Panel ini khusus untuk Administrator & Staff.</p>
                <a href="{{ route('login') }}" class="text-red-500 hover:underline mt-2 inline-block">Bukan Admin? Kembali ke Login User</a>
            </div>
        </div>
    </div>

</body>
</html>