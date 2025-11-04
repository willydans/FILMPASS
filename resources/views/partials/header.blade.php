{{-- 
  Latar belakang header ini transparan di hero, 
  tapi kita buat solid dengan border untuk contoh ini.
  Anda bisa membuatnya `absolute` dan `z-50` jika ditaruh di atas hero.
--}}
<header class="bg-slate-900 border-b border-b-slate-700/50 sticky top-0 z-50">
    <nav class="container mx-auto px-4 sm:px-6 lg:px-8 py-5 flex justify-between items-center">
        <a href="#" class="text-3xl font-extrabold text-white">
            FilmPass
        </a>
        
        <div class="hidden md:flex space-x-8">
            <a href="#" class="text-gray-300 hover:text-white transition-colors">Beranda</a>
            <a href="#" class="text-gray-300 hover:text-white transition-colors">Film</a>
            <a href="#" class="text-gray-300 hover:text-white transition-colors">Cari Film</a>
        </div>
        
        <div class="flex items-center space-x-4">
            <a href="#" class="text-gray-300 hover:text-white transition-colors">Masuk</a>
            <a href="#" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                Daftar
            </a>
        </div>
    </nav>
</header>