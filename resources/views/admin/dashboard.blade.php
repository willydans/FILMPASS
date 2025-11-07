<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - FilmPass</title>
    
    {{-- Memuat skrip dari partials/head.blade.php --}}
    @include('partials.head') 

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

    <aside id="sidebar" class="sidebar bg-white border-r border-gray-200 p-6 flex flex-col justify-between fixed h-full z-30 lg:block">
        <div>
            <div class="mb-10 flex items-center">
                <span class="text-2xl font-bold text-gray-900">
                    <span class="text-indigo-600">F</span>ilm<span class="text-indigo-600">P</span>ass Admin
                </span>
            </div>

           
            @php
                // Helper untuk menandai link aktif
                $isActive = function($route) {
                    return request()->routeIs($route) ? 'text-indigo-700 bg-indigo-50 font-semibold' : 'text-gray-600 hover:bg-gray-100';
                };
            @endphp

            <nav class="space-y-2">
                
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center p-3 rounded-lg transition duration-150 {{ $isActive('admin.dashboard') }}">
                    <i data-lucide="layout-dashboard" class="w-5 h-5 mr-3"></i>
                    Dashboard
                </a>
                
                <a href="{{ route('admin.films.index') }}" 
                   class="flex items-center p-3 rounded-lg transition duration-150 {{ $isActive('admin.films.*') }}">
                    <i data-lucide="film" class="w-5 h-5 mr-3"></i>
                    Manajemen Film
                </a>

                {{-- 
                  PERBAIKAN 2:
                  Mengganti route('admin.studio.index') (SALAH)
                  menjadi route('admin.studios.index') (BENAR, plural)
                --}}
                <a href="{{ route('admin.studios.index') }}" 
                   class="flex items-center p-3 rounded-lg transition duration-150 {{ $isActive('admin.studios.*') }}">
                    <i data-lucide="monitor" class="w-5 h-5 mr-3"></i>
                    Manajemen Studio
                </a>

                <a href="{{ route('admin.schedules.index') }}" 
                   class="flex items-center p-3 rounded-lg transition duration-150 {{ $isActive('admin.schedules.*') }}">
                    <i data-lucide="calendar" class="w-5 h-5 mr-3"></i>
                    Jadwal Tayang
                </a>
                
                <a href="#" class="flex items-center p-3 text-gray-600 hover:bg-gray-100 rounded-lg transition duration-150">
                    <i data-lucide="ticket" class="w-5 h-5 mr-3"></i>
                    Pemesanan
                </a>
                
                <a href="#" class="flex items-center p-3 text-gray-600 hover:bg-gray-100 rounded-lg transition duration-150">
                    <i data-lucide="bar-chart-2" class="w-5 h-5 mr-3"></i>
                    Laporan
                </a>
            </nav>
        </div>
    </aside>

    <div id="main-content" class="content p-0 w-full flex flex-col flex-grow">
        
        <header class="bg-white p-4 border-b border-gray-200 flex justify-between items-center fixed top-0 w-full lg:w-[calc(100%-256px)] z-20 shadow-sm">
             <button id="menu-toggle" class="text-gray-500 hover:text-gray-700 lg:hidden p-1 mr-2">
                <i data-lucide="menu" class="w-6 h-6"></i>
            </button>
            <div class="flex-grow"></div> <div class="flex items-center space-x-4">
                <button class="text-gray-500 hover:text-gray-700">
                    <i data-lucide="bell" class="w-5 h-5"></i>
                </button>
                
                <div class="flex items-center space-x-2 text-sm font-medium text-gray-700 cursor-pointer p-2 rounded-full hover:bg-gray-100 transition">
                    <div class="bg-indigo-600 text-white w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm">
                        {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                    </div>
                    <span>{{ Auth::user()->name ?? 'Admin' }}</span>
                    <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400"></i>
                </div>
            </div>
        </header>

        <main class="pt-20 px-6 pb-12 flex-grow">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Dashboard Admin</h1>
                <p class="text-gray-600">Selamat datang di panel admin FilmPass</p>
            </div>

            <div id="stats-cards" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-md border border-gray-100">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Penjualan Mingguan</h3>
                    <div id="sales-chart" class="space-y-4">
                        </div>
                </div>

                <div class="lg:col-span-1 bg-white p-6 rounded-xl shadow-md border border-gray-100">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Aksi Cepat</h3>
                    <div class="space-y-3">
                        
                        <a href="{{ route('admin.films.create') }}" class="flex items-center p-3 bg-indigo-50 text-indigo-700 font-semibold rounded-lg hover:bg-indigo-100 transition duration-200">
                            <i data-lucide="plus-circle" class="w-5 h-5 mr-3"></i>
                            Tambah Film Baru
                        </a>

                        <a href="{{ route('admin.schedules.create') }}" class="flex items-center p-3 bg-green-50 text-green-700 font-semibold rounded-lg hover:bg-green-100 transition duration-200">
                            <i data-lucide="calendar-check" class="w-5 h-5 mr-3"></i>
                            Buat Jadwal Tayang
                        </a>

                        <a href="{{ route('admin.studios.create') }}" class="flex items-center p-3 bg-orange-50 text-orange-700 font-semibold rounded-lg hover:bg-orange-100 transition duration-200">
                            <i data-lucide="monitor-dot" class="w-5 h-5 mr-3"></i>
                            Tambah Studio
                        </a>

                        <a href="#" class="flex items-center p-3 bg-purple-50 text-purple-700 font-semibold rounded-lg hover:bg-purple-100 transition duration-200">
                            <i data-lucide="bar-chart-3" class="w-5 h-5 mr-3"></i>
                            Lihat Laporan
                        </a>
                    </div>
                </div>

            </div>
            
        </main>

        <footer class="bg-gray-900 text-white mt-12 pt-12 pb-10 border-t border-gray-700">
            <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-4 gap-10">
                
                <div class="md:col-span-2 space-y-4">
                    <div class="text-2xl font-extrabold flex items-center">
                        <i data-lucide="clapperboard" class="w-6 h-6 mr-2 text-indigo-500"></i>
                        <span class="text-white">Film</span><span class="text-indigo-500">Pass</span>
                    </div>
                    <p class="text-gray-400 text-sm max-w-sm">
                        Platform pemesanan tiket bioskop online terpercaya. Nikmati pengalaman menonton film favorit Anda dengan mudah dan nyaman.
                    </p>
                    <div class="flex space-x-4 text-gray-400">
                        <a href="#" class="hover:text-white transition"><i data-lucide="facebook" class="w-5 h-5"></i></a>
                        <a href="#" class="hover:text-white transition"><i data-lucide="twitter" class="w-5 h-5"></i></a>
                        <a href="#" class="hover:text-white transition"><i data-lucide="instagram" class="w-5 h-5"></i></a>
                        <a href="#" class="hover:text-white transition"><i data-lucide="youtube" class="w-5 h-5"></i></a>
                    </div>
                </div>

                <div>
                    <h4 class="text-lg font-semibold mb-4">Tautan Cepat</h4>
                    <nav class="space-y-2 text-sm">
                        <a href="#" class="block text-gray-400 hover:text-indigo-400 transition">Beranda</a>
                        <a href="#" class="block text-gray-400 hover:text-indigo-400 transition">Film</a>
                        <a href="#" class="block text-gray-400 hover:text-indigo-400 transition">Cari Film</a>
                        <a href="#" class="block text-gray-400 hover:text-indigo-400 transition">Tentang Kami</a>
                    </nav>
                </div>

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
            <div class="max-w-7xl mx-auto px-6 mt-10 border-t border-gray-700 pt-6">
                <p class="text-center text-sm text-gray-500">&copy; {{ date('Y') }} FilmPass. All rights reserved.</p>
            </div>
        </footer>
    </div>

    <script>
        {{-- Mengambil data dinamis dari DashboardController --}}
        const totalFilm = {{ $totalFilm ?? 0 }};
        const totalPemesanan = {{ $totalPemesanan ?? 0 }};
        const totalPendapatan = {{ $totalPendapatan ?? 0 }};
        const totalPengguna = {{ $totalPengguna ?? 0 }};
        const penjualanMingguanData = @json($penjualanMingguan ?? []);

        // Kartu Statistik
        const statsData = [
            { title: "Total Film", value: totalFilm, icon: "clapperboard", bgColor: "bg-indigo-50", iconColor: "text-indigo-600", iconBg: "bg-indigo-100" },
            { title: "Total Pemesanan", value: totalPemesanan, icon: "shopping-cart", bgColor: "bg-green-50", iconColor: "text-green-600", iconBg: "bg-green-100" },
            { title: "Total Pendapatan", value: totalPendapatan, icon: "wallet", isCurrency: true, bgColor: "bg-yellow-50", iconColor: "text-yellow-600", iconBg: "bg-yellow-100" },
            { title: "Total Pengguna", value: totalPengguna, icon: "users", bgColor: "bg-purple-50", iconColor: "text-purple-600", iconBg: "bg-purple-100" },
        ];

        // Fungsi format Rupiah
        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(number);
        }

        // 1. Render Kartu Statistik
        function renderStatsCards() {
            const container = document.getElementById('stats-cards');
            let html = '';

            statsData.forEach(stat => {
                const displayValue = stat.isCurrency 
                    ? formatRupiah(stat.value).replace('IDR', 'Rp')
                    : stat.value.toLocaleString('id-ID');
                
                const cardHtml = `
                    <div class="bg-white p-5 rounded-xl shadow-md border border-gray-100 flex items-center justify-between transition-shadow duration-300 hover:shadow-lg">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">${stat.title}</p>
                            <p class="text-3xl font-bold text-gray-900">${displayValue}</p>
                        </div>
                        <div class="icon-box ${stat.iconBg} ${stat.iconColor}">
                            <i data-lucide="${stat.icon}" class="w-6 h-6"></i>
                        </div>
                    </div>
                `;
                html += cardHtml;
            });
            container.innerHTML = html;
        }

        // 2. Render Chart Penjualan Mingguan
        function renderSalesChart() {
            const container = document.getElementById('sales-chart');
            if (!penjualanMingguanData || penjualanMingguanData.length === 0) {
                container.innerHTML = '<p class="text-gray-500 text-sm">Belum ada data penjualan minggu ini.</p>';
                return;
            }
            
            const maxTotal = Math.max(...penjualanMingguanData.map(d => d.total));
            let html = '';

            penjualanMingguanData.forEach(penjualan => {
                const widthPercent = (maxTotal > 0) ? (penjualan.total / maxTotal) * 100 : 0;
                const totalRupiah = formatRupiah(penjualan.total).replace('IDR', 'Rp');

                const chartRow = `
                    <div class="flex items-center">
                        <span class="w-12 text-sm font-medium text-gray-600">${penjualan.hari}</span>
                        <div class="flex-grow mx-4">
                            <div class="sales-bar-container">
                                <div class="sales-bar-fill" style="width: ${widthPercent.toFixed(1)}%;"></div>
                            </div>
                        </div>
                        <span class="w-32 text-right text-sm font-semibold text-gray-800">${totalRupiah}</span>
                    </div>
                `;
                html += chartRow;
            });
            
            container.innerHTML = html;
        }

        // 3. Toggle Sidebar (untuk mobile)
        function setupSidebarToggle() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const toggleButton = document.getElementById('menu-toggle');
            
            if (window.innerWidth < 1024) {
                sidebar.classList.add('-translate-x-full');
            }

            toggleButton.addEventListener('click', () => {
                const isHidden = sidebar.classList.contains('-translate-x-full');
                if (isHidden) {
                    sidebar.classList.remove('-translate-x-full');
                    sidebar.classList.add('shadow-xl');
                } else {
                    sidebar.classList.add('-translate-x-full');
                    sidebar.classList.remove('shadow-xl');
                }
            });

            window.addEventListener('resize', () => {
                if (window.innerWidth >= 1024) {
                    sidebar.classList.remove('-translate-x-full');
                    sidebar.classList.remove('shadow-xl');
                } else {
                    sidebar.classList.add('-translate-x-full');
                }
            });
        }

        // Inisialisasi: Panggil semua fungsi render saat halaman dimuat
        window.onload = function() {
            renderStatsCards();
            renderSalesChart();
            lucide.createIcons(); // Inisialisasi ikon Lucide
            setupSidebarToggle();
        };
    </script>

</body>
</html>