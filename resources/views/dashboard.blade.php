<!DOCTYPE html>
<html lang="id">
<head>
    {{-- 1. MEMANGGIL ELEMEN HEAD (TERMASUK CSS) --}}
    @include('partials.head')
</head>
<body class="bg-slate-900 text-white font-sans">
    
    {{-- 2. MEMANGGIL ELEMEN HEADER --}}
    @include('partials.header')

    {{-- KONTEN UTAMA DASHBOARD --}}
    <main>
        
        <section class="relative h-[70vh] min-h-[500px] flex items-center justify-center text-center text-white" 
                 style="background-image: url('https://placehold.co/1600x700/2a2a3a/FFF?text=Hero+Background'); background-size: cover; background-position: center;">
            <div class="absolute inset-0 bg-black/60"></div>
            
            <div class="relative z-10 container mx-auto px-4">
                <h1 class="text-4xl md:text-6xl font-extrabold mb-4">Nikmati Film Favorit Anda</h1>
                <p class="text-lg md:text-xl text-gray-200 mb-8 max-w-2xl mx-auto">
                    Pesan tiket bioskop dengan mudah dan cepat. Dapatkan pengalaman menonton yang tak terlupakan dengan kualitas terbaik.
                </p>
                <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4">
                    <a href="#" class="bg-orange-500 hover:bg-orange-600 transition-colors text-white px-6 py-3 rounded-lg font-semibold text-lg inline-flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor"><path d="M15 8a.5.5 0 0 0-.5-.5H5.5a.5.5 0 0 0 0 1h9a.5.5 0 0 0 .5-.5zM3 4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V4zm1 1v10h12V5H4z"/></svg>
                        Pesan Sekarang
                    </a>
                    <a href="#" class="border-2 border-white hover:bg-white hover:text-black transition-colors text-white px-6 py-3 rounded-lg font-semibold text-lg inline-flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16zM9.555 7.168A1 1 0 0 0 8 8v4a1 1 0 0 0 1.555.832l3-2a1 1 0 0 0 0-1.664l-3-2z" clip-rule="evenodd" /></svg>
                        Lihat Trailer
                    </a>
                </div>
            </div>
        </section>

        <section class="container mx-auto px-4 sm:px-6 lg:px-8 my-20">
            <h2 class="text-3xl font-bold text-center mb-2">Film Unggulan</h2>
            <p class="text-center text-gray-400 mb-8">Film-film terbaru dan terpopuler minggu ini</p>
            
            <div class="relative rounded-lg overflow-hidden min-h-[450px] flex items-end p-6 md:p-10" 
                 style="background-image: url('https://placehold.co/1200x500/333/FFF?text=Midnight+Melody'); background-size: cover; background-position: center;">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent"></div>
                
                <div class="relative z-10 text-white">
                    <h3 class="text-4xl font-bold mb-3">Midnight Melody</h3>
                    <p class="max-w-xl mb-4 text-gray-200">
                        Drama musikal yang menyentuh hati tentang seorang pianis muda yang berjuang meraih mimpinya di tengah gempuran hidup yang berat.
                    </p>
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-2 mb-6 text-gray-300">
                        <span class="flex items-center">⭐ 8.5/10</span>
                        <span class="hidden sm:inline">|</span>
                        <span class="flex items-center">🕒 110 menit</span>
                        <span class="hidden sm:inline">|</span>
                        <span class="flex items-center">🏷️ Drama, Musik</span>
                    </div>
                    <a href="#" class="bg-orange-500 hover:bg-orange-600 transition-colors text-white px-5 py-2.5 rounded-lg font-semibold">
                        Pesan Tiket
                    </a>
                </div>
            </div>
        </section>
        
        <section class="container mx-auto px-4 sm:px-6 lg:px-8 my-20">
            <h2 class="text-3xl font-bold text-center mb-2">Film Populer</h2>
            <p class="text-center text-gray-400 mb-8">Pilihan film terbaik untuk Anda</p>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @php
                    $popularFilms = [
                        ['title' => 'Police Car', 'img' => 'https://placehold.co/400x600/444/FFF?text=Police+Car'],
                        ['title' => 'Midnight Melody', 'img' => 'https://placehold.co/400x600/333/FFF?text=Midnight+Melody'],
                        ['title' => 'Spaceship', 'img' => 'https://placehold.co/400x600/555/FFF?text=Spaceship'],
                        ['title' => 'Rainy Day', 'img' => 'https://placehold.co/400x600/666/FFF?text=Rainy+Day'],
                        ['title' => 'Comedy', 'img' => 'https://placehold.co/400x600/777/FFF?text=Comedy'],
                        ['title' => 'The Man', 'img' => 'https://placehold.co/400x600/888/FFF?text=The+Man'],
                    ];
                @endphp
                
                @foreach ($popularFilms as $film)
                <a href="#" class="rounded-lg overflow-hidden relative group block">
                    <img src="{{ $film['img'] }}" alt="{{ $film['title'] }}" class="w-full h-auto object-cover aspect-[2/3] transform group-hover:scale-105 transition-transform duration-300">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-4">
                        <h4 class="text-white text-lg font-semibold">{{ $film['title'] }}</h4>
                    </div>
                </a>
                @endforeach
            </div>
            
            <div class="text-center mt-10">
                <a href="#" class="border-2 border-gray-500 hover:border-white hover:text-white text-gray-300 px-6 py-3 rounded-lg font-semibold transition-colors">
                    Lihat Semua Film &rarr;
                </a>
            </div>
        </section>
        
        <section class="container mx-auto px-4 sm:px-6 lg:px-8 my-20">
            <h2 class="text-3xl font-bold text-center mb-2">Jelajahi Genre</h2>
            <p class="text-center text-gray-400 mb-10">Temukan film sesuai dengan selera Anda</p>
            
            <div class="flex flex-wrap justify-center gap-6 md:gap-10">
                @php
                    $genres = [
                        ['name' => 'Action', 'icon' => '💥'],
                        ['name' => 'Drama', 'icon' => '🎭'],
                        ['name' => 'Sci-Fi', 'icon' => '🚀'],
                        ['name' => 'Romance', 'icon' => '❤️'],
                        ['name' => 'Comedy', 'icon' => '😂'],
                        ['name' => 'Thriller', 'icon' => '🔪'],
                    ];
                @endphp
                
                @foreach ($genres as $genre)
                <a href="#" class="flex flex-col items-center group w-24">
                    <div class="w-20 h-20 bg-slate-800 rounded-full flex items-center justify-center text-3xl mb-3
                                group-hover:bg-orange-500 transition-colors duration-300">
                        {{ $genre['icon'] }}
                    </div>
                    <span class="font-medium text-gray-300 group-hover:text-white transition-colors">{{ $genre['name'] }}</span>
                </a>
                @endforeach
            </div>
        </section>
        
    </main>

    {{-- 3. MEMANGGIL ELEMEN FOOTER --}}
    @include('partials.footer')

</body>
</html>