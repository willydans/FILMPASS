{{-- 
  File: resources/views/partials/admin_footer.blade.php
  Footer Admin dengan gaya dark theme (sesuai request) dan link navigasi admin.
--}}

<footer class="bg-slate-900 border-t border-slate-700/50 pt-12 pb-8 text-white mt-auto">
    <div class="container mx-auto px-6 md:px-12">
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 md:gap-12">
            
            {{-- Brand & Deskripsi --}}
            <div class="md:col-span-2 space-y-4">
                <a href="{{ route('admin.dashboard') }}" class="text-2xl font-extrabold text-white inline-block">
                     <span class="text-indigo-500">Film</span>Pass <span class="text-slate-400 font-normal text-lg">Admin</span>
                </a>
                <p class="text-gray-400 text-sm leading-relaxed max-w-sm">
                    Panel kontrol terpusat untuk manajemen bioskop FilmPass. Kelola film, studio, jadwal tayang, dan laporan keuangan dengan mudah dan efisien.
                </p>
                
                {{-- Link Eksternal ke Web Utama --}}
                <div class="pt-4">
                    <a href="{{ route('home') }}" target="_blank" class="inline-flex items-center text-sm font-medium text-white bg-slate-800 hover:bg-slate-700 border border-slate-700 px-4 py-2 rounded-lg transition-colors">
                        <i data-lucide="external-link" class="w-4 h-4 mr-2 text-indigo-400"></i>
                        Lihat Website Utama
                    </a>
                </div>
            </div>

            {{-- Manajemen (Fitur Admin) --}}
            <div>
                <h4 class="font-semibold text-white mb-4 text-lg">Manajemen</h4>
                <ul class="space-y-3 text-sm">
                    <li>
                        <a href="{{ route('admin.films.index') }}" class="text-gray-400 hover:text-indigo-400 transition-colors hover:pl-1 block">
                            Manajemen Film
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.studios.index') }}" class="text-gray-400 hover:text-indigo-400 transition-colors hover:pl-1 block">
                            Manajemen Studio
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.schedules.index') }}" class="text-gray-400 hover:text-indigo-400 transition-colors hover:pl-1 block">
                            Jadwal Tayang
                        </a>
                    </li>
                    @if(Route::has('admin.users.index'))
                    <li>
                        <a href="{{ route('admin.users.index') }}" class="text-gray-400 hover:text-indigo-400 transition-colors hover:pl-1 block">
                            Pengguna
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
            
            {{-- Transaksi & Laporan --}}
            <div>
                <h4 class="font-semibold text-white mb-4 text-lg">Data & Laporan</h4>
                <ul class="space-y-3 text-sm">
                    <li>
                        <a href="{{ route('admin.bookings.index') }}" class="text-gray-400 hover:text-indigo-400 transition-colors hover:pl-1 block">
                            Daftar Pemesanan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.reports.index') }}" class="text-gray-400 hover:text-indigo-400 transition-colors hover:pl-1 block">
                            Laporan Keuangan
                        </a>
                    </li>
                    <li class="pt-2">
                         <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="text-red-400 hover:text-red-300 transition-colors hover:pl-1 text-left w-full flex items-center">
                                <i data-lucide="log-out" class="w-3 h-3 mr-2"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
        
        {{-- Copyright --}}
        <div class="mt-12 border-t border-slate-800 pt-8 flex flex-col md:flex-row justify-between items-center text-sm text-gray-500">
            <p>&copy; {{ date('Y') }} FilmPass Admin System. All rights reserved.</p>
            <p>Versi 1.0.0</p>
        </div>
    </div>
</footer>