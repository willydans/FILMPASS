<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - FilmPass</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-100">

    <div class="flex min-h-screen">
        {{-- SIDEBAR --}}
        @include('partials.admin_sidebar')

        {{-- MAIN CONTENT --}}
        <div class="flex-1 flex flex-col">
            {{-- HEADER --}}
            @include('partials.admin_header')

            {{-- CONTENT AREA --}}
            <main class="flex-grow p-6">
                <!-- JUDUL HALAMAN -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900">Dashboard Admin</h1>
                    <p class="text-gray-600">Selamat datang di panel admin FilmPass</p>
                </div>

                <!-- 4 KARTU STATISTIK UTAMA -->
                <div id="stats-cards" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Kartu akan diisi oleh JavaScript -->
                </div>

                <!-- KONTEN TENGAH (PENJUALAN & AKSI CEPAT) -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    
                    <!-- Penjualan Mingguan (Kiri) -->
                    <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-md border border-gray-100">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Penjualan Mingguan</h3>
                        
                        <div id="sales-chart" class="space-y-4">
                            <!-- Data Penjualan akan diisi oleh JavaScript -->
                        </div>
                    </div>

                    <!-- Aksi Cepat (Kanan) -->
                    <div class="lg:col-span-1 bg-white p-6 rounded-xl shadow-md border border-gray-100">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Aksi Cepat</h3>
                        <div class="space-y-3">
                            
                            <a href="#" class="flex items-center p-3 bg-indigo-50 text-indigo-700 font-semibold rounded-lg hover:bg-indigo-100 transition duration-200">
                                <i data-lucide="plus-circle" class="w-5 h-5 mr-3"></i>
                                Tambah Film Baru
                            </a>

                            <a href="#" class="flex items-center p-3 bg-green-50 text-green-700 font-semibold rounded-lg hover:bg-green-100 transition duration-200">
                                <i data-lucide="calendar-check" class="w-5 h-5 mr-3"></i>
                                Buat Jadwal Tayang
                            </a>

                            <a href="{{ route('admin.studio.index') }}" class="flex items-center p-3 bg-orange-50 text-orange-700 font-semibold rounded-lg hover:bg-orange-100 transition duration-200">
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

            {{-- FOOTER --}}
            @include('partials.admin_footer')
        </div>
    </div>

    <script>
        const totalFilm = 45;
        const totalPemesanan = 1247;
        const totalPendapatan = 125750000;
        const totalPengguna = 3456;
        
        const penjualanMingguanData = [
            { hari: 'Sen', total: 12500000 },
            { hari: 'Sel', total: 15200000 },
            { hari: 'Rab', total: 9800000 },
            { hari: 'Kam', total: 18600000 },
            { hari: 'Jum', total: 22400000 },
            { hari: 'Sab', total: 28900000 },
            { hari: 'Min', total: 25100000 },
        ];

        const statsData = [
            { title: "Total Film", value: totalFilm, icon: "clapperboard", bgColor: "bg-indigo-50", iconColor: "text-indigo-600", iconBg: "bg-indigo-100" },
            { title: "Total Pemesanan", value: totalPemesanan, icon: "shopping-cart", bgColor: "bg-green-50", iconColor: "text-green-600", iconBg: "bg-green-100" },
            { title: "Total Pendapatan", value: totalPendapatan, icon: "wallet", isCurrency: true, bgColor: "bg-yellow-50", iconColor: "text-yellow-600", iconBg: "bg-yellow-100" },
            { title: "Total Pengguna", value: totalPengguna, icon: "users", bgColor: "bg-purple-50", iconColor: "text-purple-600", iconBg: "bg-purple-100" },
        ];

        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(number);
        }

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
                        <div class="w-11 h-11 flex items-center justify-center rounded-full ${stat.iconBg} ${stat.iconColor}">
                            <i data-lucide="${stat.icon}" class="w-6 h-6"></i>
                        </div>
                    </div>
                `;
                html += cardHtml;
            });

            container.innerHTML = html;
        }

        function renderSalesChart() {
            const container = document.getElementById('sales-chart');
            const maxTotal = Math.max(...penjualanMingguanData.map(d => d.total));
            let html = '';

            penjualanMingguanData.forEach(penjualan => {
                const widthPercent = (penjualan.total / maxTotal) * 100;
                const totalRupiah = formatRupiah(penjualan.total).replace('IDR', 'Rp');

                const chartRow = `
                    <div class="flex items-center">
                        <span class="w-12 text-sm font-medium text-gray-600">${penjualan.hari}</span>
                        
                        <div class="flex-grow mx-4">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-indigo-600 h-2 rounded-full" style="width: ${widthPercent.toFixed(1)}%;"></div>
                            </div>
                        </div>

                        <span class="w-32 text-right text-sm font-semibold text-gray-800">${totalRupiah}</span>
                    </div>
                `;
                html += chartRow;
            });
            
            container.innerHTML = html;
        }

        window.onload = function() {
            renderStatsCards();
            renderSalesChart();
            lucide.createIcons();
        };
    </script>

</body>
</html>