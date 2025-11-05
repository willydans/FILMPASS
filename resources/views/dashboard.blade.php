<!DOCTYPE html>
<html lang="id">
<head>
    {{-- 1. MEMANGGIL ELEMEN HEAD (TERMASUK TAILWIND & ALPINE.JS CDN) --}}
    @include('partials.head')
</head>
<body class="bg-slate-900">
    
    {{-- 2. MEMANGGIL ELEMEN HEADER (NAVIGASI) --}}
    @include('partials.header')

    {{-- KONTEN UTAMA DASHBOARD --}}
    <main>
        
        <section class="relative h-[70vh] min-h-[500px] flex items-center" 
                 style="background-image: url('https://placehold.co/1600x700/1E293B/FFF?text=Hero+Background'); background-size: cover; background-position: center;">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/70 to-transparent"></div>
            
            <div class="relative z-10 container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-2xl">
                    <h1 class="text-4xl md:text-6xl font-extrabold mb-4 text-white">Nikmati Film Favorit Anda</h1>
                    <p class="text-lg md:text-xl text-gray-200 mb-8">
                        Pesan tiket bioskop dengan mudah dan cepat. Dapatkan pengalaman menonton yang tak terlupakan dengan kualitas terbaik.
                    </p>
                    <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                        <a href="#" class="bg-orange-500 hover:bg-orange-600 transition-colors text-white px-6 py-3 rounded-lg font-semibold text-lg inline-flex items-center justify-center">
                            <span>🎟️</span>
                            <span class="ml-2">Pesan Sekarang</span>
                        </a>
                        <a href="#" class="border-2 border-white/50 hover:bg-white hover:text-black transition-colors text-white px-6 py-3 rounded-lg font-semibold text-lg inline-flex items-center justify-center">
                            <span>▶️</span>
                            <span class="ml-2">Lihat Trailer</span>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <div class="relative z-10 -mt-24 md:-mt-32">

            <section class="container mx-auto px-4 sm:px-6 lg:px-8 my-20">
                <h2 class="text-3xl font-bold text-center mb-2">Film Unggulan</h2>
                <p class="text-center text-gray-400 mb-8">Film-film terbaru dan terpopuler minggu ini</p>
                
                @php
                    $featuredFilms = [
                        [
                            'title' => 'Midnight Melody',
                            'desc' => 'Drama musikal yang menyentuh hati tentang seorang pianis muda yang berjuang meraih mimpinya di tengah gempuran hidup yang berat.',
                            'img' => 'https://placehold.co/1200x450/1E293B/FFF?text=Midnight+Melody+Banner',
                            'tags' => ['Drama', '110 menit', 'PG-13']
                        ],
                        [
                            'title' => 'Cyber Nexus',
                            'desc' => 'Di dunia masa depan, seorang hacker harus berpacu dengan waktu untuk menghentikan AI jahat sebelum terlambat.',
                            'img' => 'https://placehold.co/1200x450/3A1E3B/FFF?text=Cyber+Nexus+Banner',
                            'tags' => ['Sci-Fi', '125 menit', 'R']
                        ],
                        [
                            'title' => 'Jejak Sang Petualang',
                            'desc' => 'Sebuah film dokumenter menakjubkan yang mengikuti penjelajah mencari kuil kuno yang hilang di hutan Amazon.',
                            'img' => 'https://placehold.co/1200x450/1E3B2A/FFF?text=Jejak+Petualang+Banner',
                            'tags' => ['Petualangan', '95 menit', 'SU']
                        ]
                    ];
                    // Menghitung jumlah film
                    $totalSlides = count($featuredFilms);
                @endphp

                {{-- 
                  Wrapper Alpine.js untuk Carousel
                  x-data: Mendefinisikan state (variabel) Alpine.js
                    - activeSlide: Slide mana yang sedang aktif (dimulai dari 1)
                    - totalSlides: Jumlah total slide (kita ambil dari PHP)
                  x-init: Menjalankan fungsi saat komponen dimuat
                    - setInterval: Menjalankan kode setiap 5000ms (5 detik)
                    - activeSlide = activeSlide % totalSlides + 1: Ini adalah trik modulus
                      untuk looping. (1->2, 2->3, 3->1)
                --}}
                <div x-data="{ activeSlide: 1, totalSlides: {{ $totalSlides }} }" 
                     x-init="setInterval(() => { activeSlide = (activeSlide % totalSlides) + 1 }, 5000)" 
                     class="relative w-full overflow-hidden rounded-lg">
                    
                    {{-- 
                      Container untuk SEMUA slide.
                      Kita menggunakan 'flex' dan menggesernya dengan 'style="transform: ..."'
                    --}}
                    <div class="flex transition-transform duration-500 ease-in-out" 
                         :style="`transform: translateX(-${(activeSlide - 1) * 100}%)`">
                        
                        {{-- Loop untuk setiap slide film --}}
                        @foreach ($featuredFilms as $film)
                        <div class="flex-shrink-0 w-full">
                            
                            {{-- Desain kartu film --}}
                            <div class="bg-slate-800/50 backdrop-blur-lg rounded-lg overflow-hidden min-h-[400px] flex flex-col md:flex-row items-center" 
                                 style="background-image: url('{{ $film['img'] }}'); background-size: cover; background-position: center 30%;">
                                
                                <div class="w-full md:w-3/5 lg:w-1/2 p-6 md:p-10 bg-gradient-to-r from-slate-900/90 via-slate-900/70 to-transparent">
                                    <h3 class="text-4xl font-bold mb-3 text-white">{{ $film['title'] }}</h3>
                                    <p class="max-w-xl mb-4 text-gray-200 text-base">
                                        {{ $film['desc'] }}
                                    </p>
                                    <div class="flex flex-wrap items-center gap-x-4 gap-y-2 mb-6 text-gray-300">
                                        <span class="bg-red-600 text-white px-2 py-0.5 rounded text-sm font-medium">{{ $film['tags'][0] }}</span>
                                        <span class="flex items-center">🕒 {{ $film['tags'][1] }}</span>
                                        <span class="border border-white/50 px-2 py-0.5 rounded text-sm">{{ $film['tags'][2] }}</span>
                                    </div>
                                    <a href="#" class="bg-orange-500 hover:bg-orange-600 transition-colors text-white px-5 py-2.5 rounded-lg font-semibold inline-block">
                                        Pesan Tiket
                                    </a>
                                </div>
                            </div>
                            
                        </div>
                        @endforeach
                        
                    </div>

                    {{-- (Opsional) Tombol Navigasi Next/Prev --}}
                    <button @click="activeSlide = (activeSlide === 1) ? totalSlides : activeSlide - 1"
                            class="absolute top-1/2 left-4 -translate-y-1/2 bg-black/30 hover:bg-black/50 text-white p-2 rounded-full z-10">
                        &#10094;
                    </button>
                    <button @click="activeSlide = (activeSlide % totalSlides) + 1"
                            class="absolute top-1/2 right-4 -translate-y-1/2 bg-black/30 hover:bg-black/50 text-white p-2 rounded-full z-10">
                        &#10095;
                    </button>

                    {{-- (Opsional) Indikator Titik (Dots) di Bawah --}}
                    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex space-x-2 z-10">
                        @for ($i = 1; $i <= $totalSlides; $i++)
                            <button @click="activeSlide = {{ $i }}" 
                                    :class="{ 'bg-white': activeSlide === {{ $i }}, 'bg-white/50': activeSlide !== {{ $i }} }" 
                                    class="w-3 h-3 rounded-full hover:bg-white transition-colors">
                            </button>
                        @endfor
                    </div>
                </div>
            </section>
            
            <section class="container mx-auto px-4 sm:px-6 lg:px-8 my-20">
                <h2 class="text-3xl font-bold text-center mb-2">Film Populer</h2>
                <p class="text-center text-gray-400 mb-8">Pilihan film terbaik untuk Anda</p>
                
                <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
                    @php
                        $popularFilms = [
                            ['title' => 'Police Car', 'img' => 'https://placehold.co/300x450/3B4A6A/FFF?text=Film+1'],
                            ['title' => 'Midnight Melody', 'img' => 'https://placehold.co/300x450/2A2A3A/FFF?text=Film+2'],
                            ['title' => 'Spaceship', 'img' => 'https://placehold.co/300x450/4A3A4A/FFF?text=Film+3'],
                            ['title' => 'Rainy Day', 'img' => 'https://placehold.co/300x450/3A4A4A/FFF?text=Film+4'],
                            ['title' => 'Comedy', 'img' => 'https://placehold.co/300x450/5A5A3A/FFF?text=Film+5'],
                            ['title' => 'The Man', 'img' => 'https://placehold.co/300x450/2A2A2A/FFF?text=Film+6'],
                        ];
                    @endphp
                    
                    @foreach ($popularFilms as $film)
                    <a href="#" class="rounded-lg overflow-hidden relative group block">
                        <img src="{{ $film['img'] }}" alt="{{ $film['title'] }}" class="w-full h-auto object-cover aspect-[2/3] transform group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent flex items-end p-3">
                            <h4 class="text-white text-lg font-semibold">{{ $film['title'] }}</h4>
                        </div>
                    </a>
                    @endforeach
                </div>
                
                <div class="text-center mt-10">
                    <a href="#" class="border-2 border-gray-500 hover:border-white hover:text-white text-gray-300 px-6 py-3 rounded-lg font-semibold transition-colors inline-flex items-center">
                        <span>Lihat Semua Film</span>
                        <span class="ml-2">&rarr;</span>
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
                                    border-2 border-transparent group-hover:border-blue-500 group-hover:bg-slate-700 transition-all duration-300">
                            {{ $genre['icon'] }}
                        </div>
                        <span class="font-medium text-gray-300 group-hover:text-white transition-colors">{{ $genre['name'] }}</span>
                    </a>
                    @endforeach
                </div>
            </section>
        
        </div> {{-- End div -mt-32 --}}
        
    </main>

    {{-- 3. MEMANGGIL ELEMEN FOOTER --}}
    @include('partials.footer')

</body>
</html>