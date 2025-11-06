<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - FilmPass</title>
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
                <!-- Dashboard (ACTIVE) -->
                <a href="#" class="flex items-center p-3 text-indigo-700 bg-indigo-50 font-semibold rounded-lg transition duration-150">
                    <i data-lucide="layout-dashboard" class="w-5 h-5 mr-3"></i>
                    Dashboard
                </a>
                <a href="#" class="flex items-center p-3 text-gray-600 hover:bg-gray-100 rounded-lg transition duration-150">
                    <i data-lucide="film" class="w-5 h-5 mr-3"></i>
                    Manajemen Film
               <a href="{{ route('admin.studio.index') }}" class="flex items-center p-3 text-gray-600 hover:bg-gray-100 rounded-lg transition duration-150">
    <i data-lucide="monitor" class="w-5 h-5 mr-3"></i>
    Manajemen Studio
</a>


                <a href="#" class="flex items-center p-3 text-gray-600 hover:bg-gray-100 rounded-lg transition duration-150">
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
                
                <!-- Info Pengguna Admin -->
                <div class="flex items-center space-x-2 text-sm font-medium text-gray-700 cursor-pointer p-2 rounded-full hover:bg-gray-100 transition">
                    <div class="bg-indigo-600 text-white w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm">A</div>
                    <span>adminfilm</span>
                    <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400"></i>
                </div>
            </div>
        </header>

        <!-- AREA UTAMA KONTEN -->
        <main class="pt-20 px-6 pb-12 flex-grow">
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

                        <a href="#" class="flex items-center p-3 bg-orange-50 text-orange-700 font-semibold rounded-lg transition duration-200">
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
        // Data Simulasi (Menggantikan variabel PHP)
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
            { hari: 'Sab', total: 28900000 }, // Maksimum
            { hari: 'Min', total: 25100000 },
        ];

        // Kartu Statistik
        const statsData = [
            { title: "Total Film", value: totalFilm, icon: "clapperboard", bgColor: "bg-indigo-50", iconColor: "text-indigo-600", iconBg: "bg-indigo-100" },
            { title: "Total Pemesanan", value: totalPemesanan, icon: "shopping-cart", bgColor: "bg-green-50", iconColor: "text-green-600", iconBg: "bg-green-100" },
            { title: "Total Pendapatan", value: totalPendapatan, icon: "wallet", isCurrency: true, bgColor: "bg-yellow-50", iconColor: "text-yellow-600", iconBg: "bg-yellow-100" },
            { title: "Total Pengguna", value: totalPengguna, icon: "users", bgColor: "bg-purple-50", iconColor: "text-purple-600", iconBg: "bg-purple-100" },
        ];

        // Fungsi untuk format mata uang Rupiah
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
            const maxTotal = Math.max(...penjualanMingguanData.map(d => d.total));
            let html = '';

            penjualanMingguanData.forEach(penjualan => {
                const widthPercent = (penjualan.total / maxTotal) * 100;
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
            // Urutan penting: Render elemen HTML dulu, baru inisialisasi ikon
            renderStatsCards();
            renderSalesChart();
            lucide.createIcons(); // Inisialisasi ikon Lucide
            setupSidebarToggle();
        };
    </script>

</body>
</html> 
