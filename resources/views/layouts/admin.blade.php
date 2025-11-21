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
        /* --- GLOBAL FUNGSI JAVASCRIPT --- */
        
        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(number);
        }

        // Toggle Sidebar (Global, ada di semua halaman)
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
            // Global initializations
            lucide.createIcons();
            setupSidebarToggle();

            // Dashboard-specific initializations (if functions exist)
            if (typeof renderStatsCards === 'function') {
                renderStatsCards();
            }
            if (typeof renderSalesChart === 'function') {
                renderSalesChart();
            }
        };
    </script>
    
    {{-- Ini untuk memuat script khusus halaman (jika ada) --}}
    @stack('scripts')
    @stack('dashboard_scripts') {{-- New stack for dashboard-specific scripts --}}
</body>
</html>