<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Judul akan dinamis, defaultnya 'Admin Dashboard' --}}
    <title>@yield('title', 'Admin Dashboard') - FilmPass</title>
    
    {{-- Memuat skrip <head> utama Anda --}}
    {{-- Pastikan 'partials/head.blade.php' memuat: --}}
    {{-- 1. Tailwind CDN (dengan ?plugins=forms) --}}
    {{-- 2. Alpine.js (defer) --}}
    {{-- 3. Font Inter --}}
    @include('partials.head') 

    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        .sidebar { width: 256px; min-height: 100vh; transition: transform 0.3s ease-in-out; }
        .content { margin-left: 0; transition: margin-left 0.3s ease-in-out; }
        @media (min-width: 1024px) { .content { margin-left: 256px; } }
        .sales-bar-container { height: 8px; border-radius: 9999px; background-color: #e5e7eb; overflow: hidden; }
        .sales-bar-fill { height: 100%; background-color: #4f46e5; }
        .icon-box { display: flex; align-items: center; justify-content: center; width: 44px; height: 44px; border-radius: 9999px; opacity: 0.8; }
    </style>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">

    @include('partials.admin_sidebar')

    <div id="main-content" class="content p-0 w-full flex flex-col flex-grow">
        
        @include('partials.admin_header')

        <main class="pt-20 px-6 pb-12 flex-grow">
            @yield('content')
        </main>

        @include('partials.admin_footer')
    </div>

    <script>
        {{-- 
          Data ini HANYA akan didefinisikan di halaman dashboard,
          tapi kita beri nilai default (?? 0) agar tidak error di halaman lain
        --}}
        const totalFilm = {{ $totalFilm ?? 0 }};
        const totalPemesanan = {{ $totalPemesanan ?? 0 }};
        const totalPendapatan = {{ $totalPendapatan ?? 0 }};
        const totalPengguna = {{ $totalPengguna ?? 0 }};
        const penjualanMingguanData = @json($penjualanMingguan ?? []);

        
        /* --- SEMUA FUNGSI JAVASCRIPT ANDA --- */
        
        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(number);
        }

        // 1. Render Kartu Statistik (HANYA jika di Dashboard)
        function renderStatsCards() {
            const container = document.getElementById('stats-cards');
            if (!container) return; // Penting: Jangan jalankan jika bukan di dashboard

            const statsData = [
                { title: "Total Film", value: totalFilm, icon: "clapperboard", bgColor: "bg-indigo-50", iconColor: "text-indigo-600", iconBg: "bg-indigo-100" },
                { title: "Total Pemesanan", value: totalPemesanan, icon: "shopping-cart", bgColor: "bg-green-50", iconColor: "text-green-600", iconBg: "bg-green-100" },
                { title: "Total Pendapatan", value: totalPendapatan, icon: "wallet", isCurrency: true, bgColor: "bg-yellow-50", iconColor: "text-yellow-600", iconBg: "bg-yellow-100" },
                { title: "Total Pengguna", value: totalPengguna, icon: "users", bgColor: "bg-purple-50", iconColor: "text-purple-600", iconBg: "bg-purple-100" },
            ];

            let html = '';
            statsData.forEach(stat => {
                const displayValue = stat.isCurrency 
                    ? formatRupiah(stat.value).replace('IDR', 'Rp')
                    : (stat.value ? stat.value.toLocaleString('id-ID') : '0');
                
                cardHtml = `
                    <div class="bg-white p-5 rounded-xl shadow-md border border-gray-100 flex items-center justify-between transition-shadow duration-300 hover:shadow-lg">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">${stat.title}</p>
                            <p class="text-3xl font-bold text-gray-900">${displayValue}</p>
                        </div>
                        <div class="icon-box ${stat.iconBg} ${stat.iconColor}">
                            <i data-lucide="${stat.icon}" class="w-6 h-6"></i>
                        </div>
                    </div>`;
                html += cardHtml;
            });
            container.innerHTML = html;
        }

        // 2. Render Chart Penjualan (HANYA jika di Dashboard)
        function renderSalesChart() {
            const container = document.getElementById('sales-chart');
            if (!container) return; // Penting: Jangan jalankan jika bukan di dashboard

            if (!penjualanMingguanData || penjualanMingguanData.length === 0) {
                container.innerHTML = '<p class="text-gray-500 text-sm">Belum ada data penjualan minggu ini.</p>';
                return;
            }
            
            const maxTotal = Math.max(...penjualanMingguanData.map(d => d.total));
            let html = '';
            penjualanMingguanData.forEach(penjualan => {
                const widthPercent = (maxTotal > 0) ? (penjualan.total / maxTotal) * 100 : 0;
                const totalRupiah = formatRupiah(penjualan.total).replace('IDR', 'Rp');

                chartRow = `
                    <div class="flex items-center">
                        <span class="w-12 text-sm font-medium text-gray-600">${penjualan.hari}</span>
                        <div class="flex-grow mx-4">
                            <div class="sales-bar-container">
                                <div class="sales-bar-fill" style="width: ${widthPercent.toFixed(1)}%;"></div>
                            </div>
                        </div>
                        <span class="w-32 text-right text-sm font-semibold text-gray-800">${totalRupiah}</span>
                    </div>`;
                html += chartRow;
            });
            container.innerHTML = html;
        }

        // 3. Toggle Sidebar (Global, ada di semua halaman)
        function setupSidebarToggle() {
            const sidebar = document.getElementById('sidebar');
            const toggleButton = document.getElementById('menu-toggle');
            
            if (sidebar && toggleButton) {
                if (window.innerWidth < 1024) {
                    sidebar.classList.add('-translate-x-full');
                }
                toggleButton.addEventListener('click', () => {
                    sidebar.classList.toggle('-translate-x-full');
                    sidebar.classList.toggle('shadow-xl');
                });
                window.addEventListener('resize', () => {
                    if (window.innerWidth >= 1024) {
                        sidebar.classList.remove('-translate-x-full', 'shadow-xl');
                    } else {
                        sidebar.classList.add('-translate-x-full');
                    }
                });
            }
        }

        // Inisialisasi: Panggil semua fungsi saat halaman dimuat
        window.onload = function() {
            renderStatsCards();     // Akan berjalan HANYA jika ada #stats-cards
            renderSalesChart();     // Akan berjalan HANYA jika ada #sales-chart
            lucide.createIcons();   // Akan merender SEMUA ikon
            setupSidebarToggle();   // Akan mengatur menu mobile
        };
    </script>
    
    {{-- Ini untuk memuat script khusus halaman (jika ada) --}}
    @stack('scripts')
</body>
</html>