<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>
    <!-- Memuat Tailwind CSS dari CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Memuat Lucide Icons untuk ikon yang bersih -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <!-- Memuat Alpine.js untuk interaktivitas (dropdown) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        /* Menggunakan font Inter */
        body { font-family: 'Inter', sans-serif; }
        .sidebar {
            width: 256px; /* Lebar sidebar */
            min-height: 100vh;
            position: fixed; /* Make sidebar fixed */
            top: 0;
            left: 0;
            z-index: 20; /* Ensure sidebar is above content */
        }
        .main-content-wrapper {
            margin-left: 256px; /* Push content to the right of the fixed sidebar */
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .header-admin {
            position: sticky; /* Make header sticky */
            top: 0;
            z-index: 10; /* Ensure header is above main content */
        }
        .main-area {
            flex-grow: 1;
            padding-top: 80px; /* Adjust based on header height */
        }
        /* Style kustom untuk progress bar (Penjualan Mingguan) */
        .sales-bar {
            height: 10px;
            border-radius: 9999px;
            background-color: #e5e7eb; /* gray-200 */
            overflow: hidden;
        }
        .sales-fill {
            height: 100%;
            background-color: #4f46e5; /* indigo-600 */
        }

        /* Responsive adjustments */
        @media (max-width: 1023px) { /* Hide sidebar on small screens */
            .sidebar {
                display: none;
            }
            .main-content-wrapper {
                margin-left: 0; /* No margin on small screens */
            }
        }
    </style>
</head>
<body class="bg-gray-50">

    @include('partials.admin_sidebar')

    <div class="main-content-wrapper">
        @include('partials.admin_header')

        <main class="main-area px-6 pb-6">
            @yield('content')
        </main>

        @include('partials.admin_footer')
    </div>

    <!-- ========================================================== -->
    <!-- LOGIC JAVASCRIPT -->
    <!-- ========================================================== -->
    <script>
        // Fungsi untuk format mata uang Rupiah
        function formatRupiah(number) {
            // Mengubah format dari 12500000 menjadi Rp 12.500.000
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(number);
        }

        // Fungsi untuk merender chart penjualan (progress bar)
        function renderSalesChart() {
            const container = document.getElementById('sales-chart');
            if (!container) return; // Exit if container not found

            const items = container.querySelectorAll('.sales-item');

            items.forEach(item => {
                const day = item.getAttribute('data-day');
                const salesValue = parseInt(item.getAttribute('data-sales'));
                const maxValue = parseInt(item.getAttribute('data-max'));
                const salesRupiah = formatRupiah(salesValue);

                // Hitung lebar bar (persentase dari nilai maksimum)
                const widthPercent = (salesValue / maxValue) * 100;

                // Hapus konten lama (elemen div kosong)
                item.innerHTML = '';

                // Buat struktur baris chart
                const chartRow = `
                    <div class="flex items-center space-x-4">
                        <span class="w-10 text-sm font-medium text-gray-600">${day}</span>

                        <div class="flex-grow">
                            <div class="sales-bar">
                                <div class="sales-fill" style="width: ${widthPercent.toFixed(1)}%;"></div>
                            </div>
                        </div>

                        <span class="w-32 text-right text-sm font-semibold text-gray-800">${salesRupiah}</span>
                    </div>
                `;
                item.innerHTML = chartRow;
            });
        }

        // Inisialisasi: Panggil fungsi render saat halaman dimuat
        window.onload = function() {
            renderSalesChart();
            lucide.createIcons(); // Inisialisasi ikon Lucide
        };
    </script>

</body>
</html>