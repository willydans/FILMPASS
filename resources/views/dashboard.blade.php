<!DOCTYPE html>
<html lang="id">
<head>
    {{-- 1. MEMANGGIL ELEMEN HEAD --}}
    @include('partials.head')
    
    {{-- Styling tambahan untuk handle gambar agar tidak gepeng --}}
    <style>
        .film-poster {
            aspect-ratio: 2/3;
            object-fit: cover;
        }
        .hero-bg {
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body class="bg-slate-900 text-white">
    
    {{-- 2. MEMANGGIL ELEMEN HEADER --}}
    @include('partials.header')

    {{-- KONTEN UTAMA --}}
    <main>
        
        {{-- HERO SECTION (STATIC) --}}
        <section class="relative h-[60vh] min-h-[500px] flex items-center hero-bg" 
                 style="background-image: url('https://images.unsplash.com/photo-1536440136628-849c177e76a1?q=80&w=2525&auto=format&fit=crop');">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/80 to-slate-900/30"></div>
            
            <div class="relative z-10 container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-2xl">
                    <h1 class="text-4xl md:text-6xl font-extrabold mb-4 text-white drop-shadow-lg">
                        Nikmati Film Favorit Anda
                    </h1>
                    <p class="text-lg md:text-xl text-gray-200 mb-8 drop-shadow-md">
                        Platform pemesanan tiket bioskop termudah. Temukan film terbaru, pilih kursi, dan nikmati pertunjukannya.
                    </p>
                    
                    {{-- Search Bar Sederhana di Hero --}}
                    <form action="{{ route('home') }}" method="GET" class="relative max-w-md">
                        <input type="text" name="search" value="{{ $search ?? '' }}" 
                               placeholder="Cari judul film atau genre..." 
                               class="w-full py-3 px-5 rounded-full bg-white/10 border border-white/30 text-white placeholder-gray-300 focus:outline-none focus:bg-slate-800/80 focus:border-orange-500 backdrop-blur-sm transition">
                        <button type="submit" class="absolute right-2 top-1.5 bg-orange-500 hover:bg-orange-600 text-white p-2 rounded-full transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </section>

        <div class="relative z-10 -mt-24 md:-mt-32">

            {{-- SECTION FILM UNGGULAN (CAROUSEL) --}}
            @if($featuredFilms->count() > 0)
            <section class="container mx-auto px-4 sm:px-6 lg:px-8 my-10">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-white">Film Terbaru</h2>
                        <p class="text-gray-400 text-sm">Tayang minggu ini</p>
                    </div>
                </div>

                @php
                    $totalSlides = $featuredFilms->count();
                @endphp

                <div x-data="{ activeSlide: 1, totalSlides: {{ $totalSlides }} }" 
                     x-init="setInterval(() => { activeSlide = (activeSlide % totalSlides) + 1 }, 6000)" 
                     class="relative w-full overflow-hidden rounded-2xl shadow-2xl border border-white/10">
                    
                    <div class="flex transition-transform duration-700 ease-in-out" 
                         :style="`transform: translateX(-${(activeSlide - 1) * 100}%)`">
                        
                        @foreach ($featuredFilms as $film)
                        <div class="flex-shrink-0 w-full relative">
                            {{-- Background Blur Image --}}
                            <div class="absolute inset-0 bg-cover bg-center blur-sm opacity-50" 
                                 style="background-image: url('{{ $film->poster_path ? asset('storage/' . $film->poster_path) : 'https://placehold.co/1200x600/1E293B/FFF?text=No+Image' }}');">
                            </div>
                            
                            <div class="relative bg-slate-900/80 backdrop-blur-md min-h-[400px] flex flex-col md:flex-row items-center p-6 md:p-10 gap-8">
                                
                                {{-- Poster Image --}}
                                <div class="w-full md:w-1/3 lg:w-1/4 flex-shrink-0">
                                    <img src="{{ $film->poster_path ? asset('storage/' . $film->poster_path) : 'https://placehold.co/400x600/1E293B/FFF?text=No+Poster' }}" 
                                         alt="{{ $film->title }}" 
                                         class="w-full rounded-lg shadow-lg object-cover aspect-[2/3]">
                                </div>

                                {{-- Film Details --}}
                                <div class="w-full md:w-2/3 lg:w-3/4 text-left">
                                    <h3 class="text-3xl md:text-4xl font-bold mb-3 text-white">{{ $film->title }}</h3>
                                    
                                    <div class="flex flex-wrap items-center gap-3 mb-4 text-sm text-gray-300">
                                        {{-- Badge Rating --}}
                                        <span class="bg-yellow-500/20 text-yellow-400 border border-yellow-500/50 px-2 py-0.5 rounded">
                                            ⭐ {{ $film->rating }}
                                        </span>
                                        
                                        {{-- Badge Durasi --}}
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            {{ $film->duration_minutes }} Menit
                                        </span>

                                        {{-- Genre Badges --}}
                                        @if($film->genre)
                                            @foreach(explode(', ', $film->genre) as $genre)
                                                <span class="bg-white/10 px-2 py-0.5 rounded hover:bg-white/20 transition cursor-default">
                                                    {{ $genre }}
                                                </span>
                                            @endforeach
                                        @endif
                                    </div>

                                    <p class="text-gray-300 text-base mb-6 line-clamp-3 leading-relaxed">
                                        {{ $film->description ?? 'Tidak ada deskripsi untuk film ini.' }}
                                    </p>
                                    
                                    <div class="flex gap-4">
                                        {{-- Tombol Pesan Tiket: Link ke Route 'ticket.create' --}}
                                        <a href="{{ route('ticket.create', $film->id) }}" 
                                           class="bg-orange-600 hover:bg-orange-700 transition-colors text-white px-6 py-3 rounded-lg font-semibold inline-flex items-center shadow-lg shadow-orange-600/30">
                                            <span class="mr-2">🎟️</span> Pesan Tiket
                                        </a>
                                        
                                        {{-- Tombol Detail: (Opsional, bisa diarahkan ke route show jika ada, atau ticket create) --}}
                                        <a href="{{ route('ticket.create', $film->id) }}" 
                                           class="border border-gray-500 hover:border-white hover:bg-white/5 text-gray-300 hover:text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                                            Detail Film
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Navigasi Slide --}}
                    <button @click="activeSlide = (activeSlide === 1) ? totalSlides : activeSlide - 1"
                            class="absolute top-1/2 left-4 -translate-y-1/2 bg-black/40 hover:bg-black/70 text-white p-3 rounded-full backdrop-blur-sm transition">
                        &#10094;
                    </button>
                    <button @click="activeSlide = (activeSlide % totalSlides) + 1"
                            class="absolute top-1/2 right-4 -translate-y-1/2 bg-black/40 hover:bg-black/70 text-white p-3 rounded-full backdrop-blur-sm transition">
                        &#10095;
                    </button>
                </div>
            </section>
            @endif
            
            {{-- SECTION FILM POPULER (GRID) --}}
            <section class="container mx-auto px-4 sm:px-6 lg:px-8 my-20" id="movies">
                <div class="flex items-end justify-between mb-8">
                    <div>
                        <h2 class="text-3xl font-bold text-white mb-2">
                            @if($search) Hasil Pencarian: "{{ $search }}" @else Film Populer @endif
                        </h2>
                        <p class="text-gray-400">Pilihan film terbaik untuk Anda</p>
                    </div>
                    @if(!$search)
                    <a href="#" class="text-orange-500 hover:text-orange-400 text-sm font-semibold flex items-center">
                        Lihat Semua <span class="ml-1">&rarr;</span>
                    </a>
                    @endif
                </div>
                
                @if($popularFilms->count() > 0)
                <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 xl:grid-cols-6 gap-6">
                    @foreach ($popularFilms as $film)
                    <div class="group relative bg-slate-800 rounded-xl overflow-hidden shadow-lg hover:shadow-2xl hover:shadow-orange-500/10 transition-all duration-300 border border-slate-700 hover:border-slate-600 hover:-translate-y-1">
                        
                        {{-- Poster Image --}}
                        <div class="relative overflow-hidden aspect-[2/3]">
                            <img src="{{ $film->poster_path ? asset('storage/' . $film->poster_path) : 'https://placehold.co/300x450/2A2A2A/FFF?text=No+Image' }}" 
                                 alt="{{ $film->title }}" 
                                 class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500">
                            
                            {{-- Overlay saat Hover --}}
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-4">
                                {{-- Tombol Pesan Sekarang di Hover --}}
                                <a href="{{ route('ticket.create', $film->id) }}" 
                                   class="bg-orange-600 text-white py-2 rounded-lg font-semibold text-sm shadow-lg w-full mb-2 text-center block hover:bg-orange-700 transition-colors">
                                    Pesan Sekarang
                                </a>
                            </div>
                            
                            {{-- Rating Badge Absolute --}}
                            <div class="absolute top-2 right-2 bg-black/60 backdrop-blur-md text-yellow-400 text-xs font-bold px-2 py-1 rounded flex items-center">
                                <span class="mr-1">⭐</span> {{ $film->rating }}
                            </div>
                        </div>

                        {{-- Card Content --}}
                        <div class="p-4">
                            <h4 class="text-white text-base font-semibold line-clamp-1 mb-1" title="{{ $film->title }}">
                                {{ $film->title }}
                            </h4>
                            <div class="flex items-center justify-between text-xs text-gray-400 mb-2">
                                {{-- Menampilkan Genre Pertama --}}
                                <span>
                                    {{ $film->genre ? explode(', ', $film->genre)[0] : '-' }}
                                </span> 
                                <span>
                                    {{ $film->release_date ? \Carbon\Carbon::parse($film->release_date)->format('Y') : '-' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-10">
                    {{ $popularFilms->appends(['search' => $search])->links() }}
                </div>

                @else
                <div class="text-center py-20 bg-slate-800/50 rounded-xl border border-dashed border-slate-700">
                    <p class="text-gray-400 text-lg">Tidak ada film yang ditemukan.</p>
                    @if($search)
                        <a href="{{ route('home') }}" class="text-orange-500 hover:underline mt-2 inline-block">Reset Pencarian</a>
                    @endif
                </div>
                @endif
            </section>
            
            {{-- SECTION GENRE --}}
            <section class="container mx-auto px-4 sm:px-6 lg:px-8 my-20 border-t border-slate-800 pt-10">
                <h2 class="text-2xl font-bold text-center mb-2 text-white">Jelajahi Genre</h2>
                <p class="text-center text-gray-400 mb-10">Temukan film sesuai dengan mood Anda</p>
                
                <div class="flex flex-wrap justify-center gap-6 md:gap-10">
                    {{-- Mengambil data $genres dari Controller --}}
                    @foreach ($genres as $genre)
                    <a href="{{ route('home', ['search' => $genre['name']]) }}" class="flex flex-col items-center group w-20 md:w-24 cursor-pointer">
                        <div class="w-16 h-16 md:w-20 md:h-20 bg-slate-800 rounded-2xl flex items-center justify-center text-2xl md:text-3xl mb-3
                                    border border-slate-700 group-hover:border-orange-500 group-hover:bg-slate-700 group-hover:scale-110 transition-all duration-300 shadow-lg">
                            {{ $genre['icon'] }}
                        </div>
                        <span class="text-sm font-medium text-gray-400 group-hover:text-white transition-colors">{{ $genre['name'] }}</span>
                    </a>
                    @endforeach
                </div>
            </section>
        
        </div> 
        
    </main>

    {{-- 3. MEMANGGIL ELEMEN FOOTER --}}
    @include('partials.footer')

</body>
</html>