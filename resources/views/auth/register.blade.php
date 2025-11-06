<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - FilmPass</title>
    <!-- Memuat Tailwind CSS dari CDN --><script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Menggunakan font Inter untuk tampilan yang bersih */
        html { font-family: 'Inter', sans-serif; }
        .background-img {
            /* URL gambar latar belakang yang baru */
            background-image: url('https://i.pinimg.com/1200x/3b/88/8a/3b888ae33caddd009ea0262a6dace304.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        /* Tinggi minimum penuh untuk memastikan latar belakang menutupi seluruh viewport */
        .min-h-full-screen {
            min-height: 100vh;
        }
    </style>
</head>
<body class="bg-gray-900 min-h-full-screen text-white">

    <!-- Latar Belakang dan Overlay --><div class="background-img absolute inset-0 -z-10">
        <!-- Overlay gelap untuk meningkatkan kontras form --><div class="absolute inset-0 bg-black opacity-60"></div>
    </div>

    <!-- Konten Utama: Form Daftar --><div class="flex flex-col items-center justify-center min-h-full-screen py-10">
        
        <!-- Form Container Tengah --><div class="bg-black bg-opacity-80 p-8 sm:p-16 rounded-lg w-full max-w-md shadow-2xl">
            <h1 class="text-4xl font-bold mb-8">Sign Up</h1>
            
            <form action="{{ route('register') }}" method="POST" class="space-y-4"> 
                @csrf
                <!-- Input Nama --><input
                    type="text"
                    id="name"
                    name="name"
                    placeholder="Name"
                    required
                    class="w-full bg-[#333] border-none text-white h-14 p-4 rounded-md focus:outline-none focus:ring-2 focus:ring-red-600"
                >

                <!-- Input Email/Nomor Telepon --><input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Email"
                    required
                    class="w-full bg-[#333] border-none text-white h-14 p-4 rounded-md focus:outline-none focus:ring-2 focus:ring-red-600"
                >

                <!-- Input Password --><input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Password"
                    required
                    class="w-full bg-[#333] border-none text-white h-14 p-4 rounded-md focus:outline-none focus:ring-2 focus:ring-red-600"
                >

                <!-- Tombol Sign Up (Warna Merah Khas) --><button
                    type="submit"
                    class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-md transition duration-200 mt-6">
                    Sign Up
                </button>
            </form>

            <!-- Tautan "Sign in" --><div class="text-gray-400 mt-4 text-base">
                Already have an account? 
                <a href="{{ route('login') }}" class="text-white hover:underline font-semibold">Sign in now.</a>
            </div>

            <!-- Teks reCAPTCHA --><div class="text-xs text-gray-500 mt-3 pt-2">
                This page is protected by Google reCAPTCHA to ensure you're not a bot. 
                <a href="#" class="text-blue-500 hover:underline">Learn more.</a>
            </div>
        </div>
    </div>
</body>
</html>