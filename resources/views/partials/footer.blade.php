{{-- 
  File: resources/views/partials/footer.blade.php
  Footer User dengan integrasi Vite & Tailwind
  
  Perubahan:
  1. Link 'Beranda' -> route('home')
  2. Link 'Film' -> route('movies.index')
  3. Link 'Cari Film' -> sementara ke route('movies.index') karena pencarian ada di sana
--}}

<footer class="bg-slate-900 border-t border-slate-700/50 pt-12 pb-8 text-white mt-auto">
    <div class="container mx-auto px-6 md:px-12">
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 md:gap-12">
            
            {{-- Brand & Deskripsi --}}
            <div class="md:col-span-2 space-y-4">
                <a href="{{ route('home') }}" class="text-2xl font-extrabold text-white inline-block">
                     <span class="text-indigo-500">Film</span>Pass
                </a>
                <p class="text-gray-400 text-sm leading-relaxed max-w-sm">
                    Platform pemesanan tiket bioskop online terpercaya. Nikmati kemudahan nonton film favorit Anda dengan mudah, cepat, dan aman.
                </p>
                
                {{-- Social Media Icons (Placeholder) --}}
                <div class="flex space-x-4 pt-2">
                    <a href="#" class="text-gray-400 hover:text-white transition-colors p-2 rounded-full hover:bg-slate-800">
                        <i data-lucide="facebook" class="w-5 h-5"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors p-2 rounded-full hover:bg-slate-800">
                        <i data-lucide="twitter" class="w-5 h-5"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors p-2 rounded-full hover:bg-slate-800">
                        <i data-lucide="instagram" class="w-5 h-5"></i>
                    </a>
                </div>
            </div>

            {{-- Tautan Cepat (Disesuaikan dengan Rute) --}}
            <div>
                <h4 class="font-semibold text-white mb-4 text-lg">Tautan Cepat</h4>
                <ul class="space-y-3 text-sm">
                    <li>
                        <a href="{{ route('home') }}" class="text-gray-400 hover:text-indigo-400 transition-colors hover:pl-1 block {{ request()->routeIs('home') ? 'text-indigo-400 font-medium' : '' }}">
                            Beranda
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('movies.index') }}" class="text-gray-400 hover:text-indigo-400 transition-colors hover:pl-1 block {{ request()->routeIs('movies.*') ? 'text-indigo-400 font-medium' : '' }}">
                            Film
                        </a>
                    </li>
                    <li>
                        {{-- Cari Film mengarah ke halaman daftar film --}}
                        <a href="{{ route('movies.index') }}" class="text-gray-400 hover:text-indigo-400 transition-colors hover:pl-1 block">
                            Cari Film
                        </a>
                    </li>
                </ul>
            </div>
            
            {{-- Bantuan (Link Placeholder - biasanya halaman statis) --}}
            <div>
                <h4 class="font-semibold text-white mb-4 text-lg">Bantuan</h4>
                <ul class="space-y-3 text-sm">
                    <li><a href="#" class="text-gray-400 hover:text-indigo-400 transition-colors hover:pl-1 block">Pusat Bantuan</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-indigo-400 transition-colors hover:pl-1 block">Hubungi Kami</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-indigo-400 transition-colors hover:pl-1 block">Syarat & Ketentuan</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-indigo-400 transition-colors hover:pl-1 block">Kebijakan Privasi</a></li>
                </ul>
            </div>
        </div>
        
        {{-- Copyright --}}
        <div class="mt-12 border-t border-slate-800 pt-8 text-center">
            <p class="text-gray-500 text-sm">&copy; {{ date('Y') }} FilmPass. Semua hak cipta dilindungi.</p>
        </div>
    </div>
</footer>