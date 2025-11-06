<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'FilmPass Admin')</title>
    <!-- Memuat Tailwind CSS dari CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Memuat Lucide Icons untuk ikon yang bersih dan modern -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <style>
        /* Menggunakan font Inter */
        body { font-family: 'Inter', sans-serif; }
        .sidebar {
            width: 256px; /* Lebar sidebar */
            min-height: 100vh;
            transition: transform 0.3s ease-in-out;
        }
        /* Mengatur agar konten dimulai setelah sidebar */
        .content {
            margin-left: 0;
            transition: margin-left 0.3s ease-in-out;
        }
        @media (min-width: 1024px) {
            .content {
                margin-left: 256px;
            }
        }
        .sales-bar-container {
            height: 8px; /* Tinggi bar */
            border-radius: 9999px;
            background-color: #e5e7eb; /* gray-200 */
            overflow: hidden;
        }
        .sales-bar-fill {
            height: 100%;
            background-color: #4f46e5; /* indigo-600 */
        }
        
        /* Ikon Kustom untuk match visual dashboard */
        .icon-box {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            border-radius: 9999px;
            opacity: 0.8;
        }
    </style>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">

    <!-- SIDEBAR NAVIGASI (KIRI) -->
    <aside id="sidebar" class="sidebar bg-white border-r border-gray-200 p-6 flex flex-col justify-between fixed h-full z-30 lg:block">
        <div>
            <!-- Logo dan Nama Aplikasi -->
            <div class="mb-10 flex items-center">
                <span class="text-2xl font-bold text-gray-900">
                    <span class="text-indigo-600">F</span>ilm<span class="text-indigo-600">P</span>ass Admin
                </span>
            </div>

            <!-- Menu Navigasi -->
            <nav class="space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center p-3 rounded-lg transition duration-150 @if(request()->routeIs('admin.dashboard')) text-indigo-700 bg-indigo-50 font-semibold @else text-gray-600 hover:bg-gray-100 @endif">
                    <i data-lucide="layout-dashboard" class="w-5 h-5 mr-3"></i>
                    Dashboard
                </a>
                <a href="{{ route('admin.films.index') }}" class="flex items-center p-3 rounded-lg transition duration-150 @if(request()->routeIs('admin.films.index')) text-indigo-700 bg-indigo-50 font-semibold @else text-gray-600 hover:bg-gray-100 @endif">
                    <i data-lucide="film" class="w-5 h-5 mr-3"></i>
                    Manajemen Film
                </a>
                <a href="{{ route('admin.studio.index') }}" class="flex items-center p-3 rounded-lg transition duration-150 @if(request()->routeIs('admin.studio.index')) text-indigo-700 bg-indigo-50 font-semibold @else text-gray-600 hover:bg-gray-100 @endif">
                    <i data-lucide="monitor" class="w-5 h-5 mr-3"></i>
                    Manajemen Studio
                </a>
                <a href="{{ route('admin.schedules.index') }}" class="flex items-center p-3 rounded-lg transition duration-150 @if(request()->routeIs('admin.schedules.index')) text-indigo-700 bg-indigo-50 font-semibold @else text-gray-600 hover:bg-gray-100 @endif">
                    <i data-lucide="calendar" class="w-5 h-5 mr-3"></i>
                    Jadwal Tayang
                </a>
                <a href="{{ route('admin.bookings.index') }}" class="flex items-center p-3 rounded-lg transition duration-150 @if(request()->routeIs('admin.bookings.index')) text-indigo-700 bg-indigo-50 font-semibold @else text-gray-600 hover:bg-gray-100 @endif">
                    <i data-lucide="ticket" class="w-5 h-5 mr-3"></i>
                    Pemesanan
                </a>
                <a href="{{ route('admin.reports.index') }}" class="flex items-center p-3 rounded-lg transition duration-150 @if(request()->routeIs('admin.reports.index')) text-indigo-700 bg-indigo-50 font-semibold @else text-gray-600 hover:bg-gray-100 @endif">
                    <i data-lucide="bar-chart-2" class="w-5 h-5 mr-3"></i>
                    Laporan
                </a>
            </nav>
        </div>
    </aside>

    <!-- KONTEN UTAMA & HEADER WRAPPER -->
    <div id="main-content" class="content p-0 w-full flex flex-col flex-grow">
        
        <!-- HEADER (ATAS) -->
        <header class="bg-white p-4 border-b border-gray-200 flex justify-between items-center fixed top-0 w-full lg:w-[calc(100%-256px)] z-20 shadow-sm">
             <!-- Tombol Menu (Hanya terlihat di mobile) -->
            <button id="menu-toggle" class="text-gray-500 hover:text-gray-700 lg:hidden p-1 mr-2">
                <i data-lucide="menu" class="w-6 h-6"></i>
            </button>
            <div class="flex-grow"></div> <!-- Spacer -->
            <div class="flex items-center space-x-4">
                <!-- Tombol Notifikasi -->
                <button class="text-gray-500 hover:text-gray-700">
                    <i data-lucide="bell" class="w-5 h-5"></i>
                </button>
                
                <!-- Info Pengguna Admin dengan Dropdown Logout -->
                <div class="relative group">
                    <div class="flex items-center space-x-2 text-sm font-medium text-gray-700 cursor-pointer p-2 rounded-full hover:bg-gray-100 transition" id="admin-menu-button">
                        <div class="bg-indigo-600 text-white w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm">A</div>
                        <span>adminfilm</span>
                        <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400"></i>
                    </div>

                    <!-- Dropdown Menu -->
                    <div id="admin-dropdown-menu" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 ring-1 ring-black ring-opacity-5 focus:outline-none hidden group-hover:block">
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-red-100">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- AREA UTAMA KONTEN -->
        <main class="pt-20 px-6 pb-12 flex-grow">
            @yield('content')
        </main>

        <!-- FOOTER -->
        <footer class="bg-gray-900 text-white mt-12 pt-12 pb-10 border-t border-gray-700">
            <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-4 gap-10">
                
                <!-- Kolom 1: Logo dan Deskripsi -->
                <div class="md:col-span-2 space-y-4">
                    <div class="text-2xl font-extrabold flex items-center">
                        <i data-lucide="clapperboard" class="w-6 h-6 mr-2 text-indigo-500"></i>
                        <span class="text-white">Film</span><span class="text-indigo-500">Pass</span>
                    </div>
                    <p class="text-gray-400 text-sm max-w-sm">
                        Platform pemesanan tiket bioskop online terpercaya. Nikmati pengalaman menonton film favorit Anda dengan mudah dan nyaman.
                    </p>
                    <div class="flex space-x-4 text-gray-400">
                        <!-- Ikon Media Sosial -->
                        <a href="#" class="hover:text-white transition"><i data-lucide="facebook" class="w-5 h-5"></i></a>
                        <a href="#" class="hover:text-white transition"><i data-lucide="twitter" class="w-5 h-5"></i></a>
                        <a href="#" class="hover:text-white transition"><i data-lucide="instagram" class="w-5 h-5"></i></a>
                        <a href="#" class="hover:text-white transition"><i data-lucide="youtube" class="w-5 h-5"></i></a>
                    </div>
                </div>

                <!-- Kolom 2: Tautan Cepat -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">Tautan Cepat</h4>
                    <nav class="space-y-2 text-sm">
                        <a href="#" class="block text-gray-400 hover:text-indigo-400 transition">Beranda</a>
                        <a href="#" class="block text-gray-400 hover:text-indigo-400 transition">Film</a>
                        <a href="#" class="block text-gray-400 hover:text-indigo-400 transition">Cari Film</a>
                        <a href="#" class="block text-gray-400 hover:text-indigo-400 transition">Tentang Kami</a>
                    </nav>
                </div>

                <!-- Kolom 3: Bantuan -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">Bantuan</h4>
                    <nav class="space-y-2 text-sm">
                        <a href="#" class="block text-gray-400 hover:text-indigo-400 transition">Pusat Bantuan</a>
                        <a href="#" class="block text-gray-400 hover:text-indigo-400 transition">Hubungi Kami</a>
                        <a href="#" class="block text-gray-400 hover:text-indigo-400 transition">Syarat & Ketentuan</a>
                        <a href="#" class="block text-gray-400 hover:text-indigo-400 transition">Kebijakan Privasi</a>
                    </nav>
                </div>

            </div>
            <!-- Bagian Hak Cipta -->
            <div class="max-w-7xl mx-auto px-6 mt-10 border-t border-gray-700 pt-6">
                <p class="text-center text-sm text-gray-500">&copy; 2024 FilmPass. All rights reserved.</p>
            </div>
        </footer>
    </div>

    <!-- LOGIC JAVASCRIPT -->
    <script>
        // 3. Toggle Sidebar (untuk mobile)
        function setupSidebarToggle() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const toggleButton = document.getElementById('menu-toggle');
            
            // Set posisi awal untuk mobile (tersembunyi)
            if (window.innerWidth < 1024) {
                sidebar.classList.add('-translate-x-full');
            }

            toggleButton.addEventListener('click', () => {
                const isHidden = sidebar.classList.contains('-translate-x-full');
                
                if (isHidden) {
                    sidebar.classList.remove('-translate-x-full');
                    sidebar.classList.add('shadow-xl');
                    // Tambahkan overlay jika perlu, tapi untuk demo ini biarkan
                } else {
                    sidebar.classList.add('-translate-x-full');
                    sidebar.classList.remove('shadow-xl');
                }
            });

            // Sembunyikan sidebar saat resize ke desktop, pastikan tidak ada class -translate-x-full di desktop
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 1024) {
                    sidebar.classList.remove('-translate-x-full');
                    sidebar.classList.remove('shadow-xl');
                } else {
                     // Sembunyikan jika kembali ke mobile
                    sidebar.classList.add('-translate-x-full');
                }
            });
        }


        // Inisialisasi: Panggil semua fungsi render saat halaman dimuat
        window.onload = function() {
            lucide.createIcons(); // Inisialisasi ikon Lucide
            setupSidebarToggle();
        };
    </script>
    @yield('scripts')

</body>
</html>