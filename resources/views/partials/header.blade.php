{{-- 
  Header ini dibuat 'absolute' agar menimpa gambar hero di dashboard.
  'z-10' memastikannya berada di lapisan atas.
--}}
<header class="absolute top-0 left-0 right-0 z-10 bg-slate-900/80 backdrop-blur-md border-b border-slate-700/50">
    <nav class="container mx-auto px-4 sm:px-6 lg:px-8 py-5 flex justify-between items-center">
        
        <a href="#" class="text-3xl font-extrabold text-white">
            <span class="text-blue-500">Film</span>Pass
        </a>
        
        <div class="hidden md:flex space-x-8">
            <a href="/" class="text-gray-300 hover:text-white transition-colors">Beranda</a>
            <a href="/movies" class="text-gray-300 hover:text-white transition-colors">Film</a>
            <a href="/search" class="text-gray-300 hover:text-white transition-colors">Cari Film</a>

            {{-- Tampilkan menu ini hanya jika user SUDAH login --}}
            @auth
                <a href="{{ route('riwayat')}}" class="text-gray-300 hover:text-white transition-colors">Riwayat Pesanan</a>
            @endauth
        </div>
        
        <div class="flex items-center space-x-4">
            {{-- Jika user BELUM login --}}
            @guest
                <a href="{{ route('login') }}" class="text-gray-300 hover:text-white transition-colors font-medium">Masuk</a>
                <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    Daftar
                </a>
            @endguest

            {{-- Jika user SUDAH login --}}
            @auth
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        Logout
                    </button>
                </form>
            @endauth
        </div>
    </nav>
</header>
