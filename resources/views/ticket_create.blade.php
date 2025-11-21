<!DOCTYPE html>
<html lang="id">
<head>
    @include('partials.head')
    <title>{{ $film->title }} - Pesan Tiket</title>
    <style>
        /* Efek Glassmorphism untuk header */
        .glass-header {
            background: linear-gradient(to bottom, rgba(15, 23, 42, 0) 0%, rgba(15, 23, 42, 1) 100%);
        }
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</head>
<body class="bg-slate-900 text-white font-inter">

    @include('partials.header')

    <main>
        {{-- 1. HERO SECTION (GAMBAR LATAR BELAKANG) --}}
        <div class="relative h-[60vh] min-h-[400px]">
            {{-- Background Image --}}
            <div class="absolute inset-0">
                <img src="{{ $film->poster_path ? asset('storage/' . $film->poster_path) : 'https://placehold.co/1920x1080/1e293b/FFF?text=No+Backdrop' }}" 
                     class="w-full h-full object-cover object-center opacity-40" alt="Backdrop">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/60 to-transparent"></div>
            </div>

            {{-- Hero Content (Judul di bawah) --}}
            <div class="absolute bottom-0 left-0 w-full pb-10 pt-20 glass-header">
                <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row items-end gap-6">
                    
                    {{-- Poster Kecil (Hanya muncul di Desktop) --}}
                    <div class="hidden md:block w-48 flex-shrink-0 shadow-2xl rounded-lg overflow-hidden border-2 border-slate-700 transform translate-y-16 bg-slate-800">
                        <img src="{{ $film->poster_path ? asset('storage/' . $film->poster_path) : 'https://placehold.co/300x450/1e293b/FFF?text=No+Poster' }}" 
                             class="w-full h-full object-cover aspect-[2/3]">
                    </div>

                    {{-- Info Judul --}}
                    <div class="flex-grow">
                        <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-4 drop-shadow-lg">{{ $film->title }}</h1>
                        
                        <div class="flex flex-wrap items-center gap-4 text-sm md:text-base font-medium text-gray-300">
                            {{-- Genre Badge --}}
                            <span class="px-3 py-1 bg-orange-600 text-white rounded-full text-xs font-bold uppercase tracking-wide">
                                {{ explode(',', $film->genre)[0] }}
                            </span>

                            <div class="flex items-center">
                                <i data-lucide="clock" class="w-4 h-4 mr-2 text-gray-400"></i>
                                {{ $film->duration_minutes }} Menit
                            </div>

                            <div class="flex items-center">
                                <i data-lucide="calendar" class="w-4 h-4 mr-2 text-gray-400"></i>
                                {{ \Carbon\Carbon::parse($film->release_date)->isoFormat('D MMMM Y') }}
                            </div>

                            <div class="flex items-center">
                                <i data-lucide="star" class="w-4 h-4 mr-2 text-yellow-500"></i>
                                {{ $film->rating }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. MAIN CONTENT (GRID) --}}
        <div class="max-w-7xl mx-auto px-6 py-12 md:mt-12">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                
                {{-- KOLOM KIRI (SINOPSIS & JADWAL) --}}
                <div class="lg:col-span-2 space-y-10">
                    
                    {{-- Section Sinopsis --}}
                    <section class="bg-slate-800/50 p-6 rounded-2xl border border-slate-700/50">
                        <h3 class="text-xl font-bold text-white mb-4 border-l-4 border-indigo-500 pl-3">Sinopsis</h3>
                        <p class="text-gray-300 leading-relaxed text-justify">
                            {{ $film->description ?? 'Belum ada sinopsis untuk film ini.' }}
                        </p>
                    </section>

                    {{-- Section Trailer (Placeholder Youtube) --}}
                    <section>
                        <h3 class="text-xl font-bold text-white mb-4 border-l-4 border-indigo-500 pl-3">Trailer</h3>
                        <div class="aspect-video bg-black rounded-2xl overflow-hidden shadow-lg relative group cursor-pointer">
                             {{-- Dummy Iframe / Gambar --}}
                             <img src="https://placehold.co/800x450/000/FFF?text=Trailer+Placeholder" class="w-full h-full object-cover opacity-70 group-hover:opacity-100 transition">
                             <div class="absolute inset-0 flex items-center justify-center">
                                 <div class="w-16 h-16 bg-red-600 rounded-full flex items-center justify-center shadow-lg group-hover:scale-110 transition transform">
                                     <i data-lucide="play" class="w-8 h-8 text-white fill-current"></i>
                                 </div>
                             </div>
                        </div>
                    </section>

                    {{-- Section Jadwal Tayang (SINKRON DB) --}}
                    <section id="jadwal">
                        <h3 class="text-xl font-bold text-white mb-6 border-l-4 border-indigo-500 pl-3">Jadwal Tayang</h3>

                        @if($schedules->count() > 0)
                            <div class="space-y-4">
                                @foreach($schedules as $schedule)
                                <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm hover:shadow-md transition duration-200 flex flex-col sm:flex-row items-center justify-between group">
                                    
                                    {{-- Info Studio & Waktu --}}
                                    <div class="flex-grow mb-4 sm:mb-0 text-center sm:text-left">
                                        <div class="flex items-center justify-center sm:justify-start gap-2 mb-1">
                                            <h4 class="font-bold text-slate-900 text-lg">{{ $schedule->studio->name }}</h4>
                                            <span class="px-2 py-0.5 bg-indigo-100 text-indigo-700 text-xs font-bold rounded uppercase">
                                                {{ $schedule->studio->type }}
                                            </span>
                                        </div>
                                        
                                        <div class="text-sm text-gray-500 mb-2">
                                            {{ \Carbon\Carbon::parse($schedule->start_time)->isoFormat('dddd, D MMMM Y') }}
                                        </div>

                                        <div class="flex items-center justify-center sm:justify-start gap-4 text-sm">
                                            <div class="flex items-center text-slate-700 font-medium">
                                                <i data-lucide="clock" class="w-4 h-4 mr-1.5 text-gray-400"></i>
                                                {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} 
                                                <span class="mx-1">-</span> 
                                                {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                            </div>
                                            <div class="flex items-center text-slate-600" title="Kursi Tersedia">
                                                <i data-lucide="users" class="w-4 h-4 mr-1.5 text-gray-400"></i>
                                                {{ $schedule->available_seats }} Kursi
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Harga & Tombol --}}
                                    <div class="text-center sm:text-right flex flex-col items-center sm:items-end gap-2">
                                        <div class="text-indigo-600 font-extrabold text-xl">
                                            Rp {{ number_format($schedule->price, 0, ',', '.') }}
                                        </div>
                                        
                                        {{-- UBAH DISINI: Link ke Halaman Pilih Kursi --}}
                                        <a href="{{ route('booking.seats', $schedule->id) }}" 
                                           class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2.5 rounded-lg font-semibold shadow-md shadow-orange-500/20 transition-all transform group-hover:-translate-y-1 flex items-center">
                                            <i data-lucide="sofa" class="w-4 h-4 mr-2"></i>
                                            Pilih Kursi
                                        </a>
                                    </div>

                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-10 bg-slate-800/50 rounded-xl border border-dashed border-slate-700">
                                <i data-lucide="calendar-off" class="w-12 h-12 text-gray-500 mx-auto mb-3"></i>
                                <p class="text-gray-400">Belum ada jadwal tayang untuk film ini.</p>
                            </div>
                        @endif
                    </section>

                </div>

                {{-- KOLOM KANAN (INFO & POSTER MOBILE) --}}
                <div class="lg:col-span-1 space-y-8">
                    
                    {{-- Poster di Mobile (Muncul di atas grid) --}}
                    <div class="block md:hidden rounded-xl overflow-hidden shadow-xl border border-slate-700">
                        <img src="{{ $film->poster_path ? asset('storage/' . $film->poster_path) : 'https://placehold.co/400x600/1e293b/FFF?text=No+Poster' }}" 
                             class="w-full h-auto">
                    </div>

                    {{-- Informasi Film Card --}}
                    <div class="bg-slate-800 p-6 rounded-2xl border border-slate-700 shadow-lg">
                        <h4 class="font-bold text-white mb-4 pb-2 border-b border-slate-700">Informasi Film</h4>
                        
                        <div class="space-y-4 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-400">Genre</span>
                                <span class="text-white font-medium text-right">{{ $film->genre }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Durasi</span>
                                <span class="text-white font-medium">{{ $film->duration_minutes }} Menit</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Rating Usia</span>
                                <span class="text-white font-medium">{{ $film->rating }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Rilis</span>
                                <span class="text-white font-medium">{{ \Carbon\Carbon::parse($film->release_date)->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Aksi Cepat --}}
                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-lg">
                        <h4 class="font-bold text-slate-900 mb-4">Aksi Cepat</h4>
                        <div class="space-y-3">
                            {{-- Link smooth scroll ke section jadwal --}}
                            <a href="#jadwal" class="block w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-center rounded-lg font-bold transition shadow-lg shadow-indigo-600/20">
                                <i data-lucide="ticket" class="w-4 h-4 inline mr-2"></i> Pesan Tiket
                            </a>
                            <button class="block w-full py-3 border border-slate-300 hover:bg-slate-50 text-slate-700 text-center rounded-lg font-semibold transition flex items-center justify-center">
                                <i data-lucide="heart" class="w-4 h-4 mr-2"></i> Tambah ke Favorit
                            </button>
                            <button class="block w-full py-3 text-indigo-600 hover:text-indigo-800 text-center text-sm font-semibold transition">
                                <i data-lucide="share-2" class="w-4 h-4 inline mr-1"></i> Bagikan Film
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>

    @include('partials.footer')
    
    <script>
        lucide.createIcons();
    </script>
</body>
</html>