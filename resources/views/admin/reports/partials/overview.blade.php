{{-- KARTU STATISTIK UTAMA --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    
    {{-- Total Pendapatan --}}
    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-green-100 p-3 rounded-full">
                <i data-lucide="wallet" class="w-6 h-6 text-green-600"></i>
            </div>
        </div>
        <h3 class="text-gray-600 text-sm font-medium mb-1">Total Pendapatan</h3>
        <p class="text-3xl font-bold text-gray-900">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</p>
        <p class="text-sm text-gray-500 mt-2">Periode: {{ $startDate }} - {{ $endDate }}</p>
    </div>

    {{-- Total Pemesanan --}}
    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-blue-100 p-3 rounded-full">
                <i data-lucide="shopping-cart" class="w-6 h-6 text-blue-600"></i>
            </div>
        </div>
        <h3 class="text-gray-600 text-sm font-medium mb-1">Total Pemesanan</h3>
        <p class="text-3xl font-bold text-gray-900">{{ number_format($totalBookings ?? 0, 0, ',', '.') }}</p>
        <p class="text-sm text-green-600 mt-2">
            ✓ {{ $confirmedBookings ?? 0 }} Confirmed | ✗ {{ $cancelledBookings ?? 0 }} Cancelled
        </p>
    </div>

    {{-- Total Tiket Terjual --}}
    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-purple-100 p-3 rounded-full">
                <i data-lucide="ticket" class="w-6 h-6 text-purple-600"></i>
            </div>
        </div>
        <h3 class="text-gray-600 text-sm font-medium mb-1">Tiket Terjual</h3>
        <p class="text-3xl font-bold text-gray-900">{{ number_format($totalTickets ?? 0, 0, ',', '.') }}</p>
        <p class="text-sm text-gray-500 mt-2">Total kursi terisi</p>
    </div>

    {{-- Rata-rata Harga --}}
    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-orange-100 p-3 rounded-full">
                <i data-lucide="trending-up" class="w-6 h-6 text-orange-600"></i>
            </div>
        </div>
        <h3 class="text-gray-600 text-sm font-medium mb-1">Rata-rata Transaksi</h3>
        <p class="text-3xl font-bold text-gray-900">Rp {{ number_format($avgTicketPrice ?? 0, 0, ',', '.') }}</p>
        <p class="text-sm text-gray-500 mt-2">Per pemesanan</p>
    </div>
</div>

{{-- GRAFIK PENDAPATAN HARIAN --}}
<div class="bg-white p-6 rounded-xl shadow-md mb-8">
    <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
        <i data-lucide="line-chart" class="w-5 h-5 mr-2 text-indigo-600"></i>
        Grafik Pendapatan Harian
    </h3>
    
    <canvas id="revenueChart" height="80"></canvas>
</div>

{{-- TABEL RINGKASAN HARIAN --}}
<div class="bg-white p-6 rounded-xl shadow-md">
    <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
        <i data-lucide="calendar" class="w-5 h-5 mr-2 text-indigo-600"></i>
        Ringkasan Harian
    </h3>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pemesanan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pendapatan</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($dailyRevenue ?? [] as $day)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ \Carbon\Carbon::parse($day->date)->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $day->bookings }} pemesanan
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">
                        Rp {{ number_format($day->revenue, 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-6 py-8 text-center text-gray-500">
                        <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-3 text-gray-400"></i>
                        <p>Belum ada data untuk periode ini</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
// Data untuk Chart.js
const dailyData = @json($dailyRevenue ?? []);
const labels = dailyData.map(d => {
    const date = new Date(d.date);
    return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
});
const revenues = dailyData.map(d => d.revenue);

// Buat Chart
const ctx = document.getElementById('revenueChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Pendapatan (Rp)',
            data: revenues,
            borderColor: 'rgb(99, 102, 241)',
            backgroundColor: 'rgba(99, 102, 241, 0.1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointRadius: 5,
            pointHoverRadius: 7,
            pointBackgroundColor: 'rgb(99, 102, 241)',
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: true,
                position: 'top',
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'Rp ' + (value / 1000) + 'k';
                    }
                }
            }
        }
    }
});

// Reinitialize icons after dynamic content
lucide.createIcons();
</script>