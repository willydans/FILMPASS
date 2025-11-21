{{-- HEADER INFO --}}
<div class="bg-gradient-to-r from-pink-600 to-rose-600 text-white p-8 rounded-xl shadow-lg mb-8">
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <h2 class="text-3xl font-bold mb-2">👥 Laporan Pengguna</h2>
            <p class="text-pink-100">Statistik pelanggan dan aktivitas pemesanan</p>
        </div>
        <div class="flex gap-6">
            <div class="text-center">
                <p class="text-sm opacity-90 mb-1">Total Pengguna</p>
                <p class="text-3xl font-bold">{{ $totalUsers ?? 0 }}</p>
            </div>
            <div class="text-center">
                <p class="text-sm opacity-90 mb-1">Pengguna Baru</p>
                <p class="text-3xl font-bold text-yellow-300">{{ $newUsers ?? 0 }}</p>
            </div>
        </div>
    </div>
</div>

{{-- STATISTIK RINGKASAN --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    
    <div class="bg-white p-6 rounded-xl shadow-md">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-purple-100 p-3 rounded-full">
                <i data-lucide="users" class="w-6 h-6 text-purple-600"></i>
            </div>
        </div>
        <h3 class="text-gray-600 text-sm font-medium mb-1">Total Pelanggan Aktif</h3>
        <p class="text-3xl font-bold text-gray-900">{{ $topUsers->count() ?? 0 }}</p>
        <p class="text-sm text-gray-500 mt-2">Yang melakukan pemesanan</p>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-md">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-green-100 p-3 rounded-full">
                <i data-lucide="shopping-bag" class="w-6 h-6 text-green-600"></i>
            </div>
        </div>
        <h3 class="text-gray-600 text-sm font-medium mb-1">Total Pemesanan</h3>
        <p class="text-3xl font-bold text-gray-900">{{ $topUsers->sum('total_bookings') ?? 0 }}</p>
        <p class="text-sm text-gray-500 mt-2">Periode: {{ $startDate }} - {{ $endDate }}</p>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-md">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-yellow-100 p-3 rounded-full">
                <i data-lucide="dollar-sign" class="w-6 h-6 text-yellow-600"></i>
            </div>
        </div>
        <h3 class="text-gray-600 text-sm font-medium mb-1">Total Transaksi</h3>
        <p class="text-3xl font-bold text-gray-900">
            Rp {{ number_format($topUsers->sum('total_spent') ?? 0, 0, ',', '.') }}
        </p>
        <p class="text-sm text-gray-500 mt-2">Dari semua pelanggan</p>
    </div>
</div>

{{-- TOP PELANGGAN --}}
<div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
    <div class="p-6 border-b border-gray-200">
        <h3 class="text-xl font-bold text-gray-900 flex items-center">
            <i data-lucide="trophy" class="w-5 h-5 mr-2 text-yellow-600"></i>
            Top 20 Pelanggan VIP
        </h3>
        <p class="text-sm text-gray-600 mt-1">Pelanggan dengan total pembelanjaan tertinggi</p>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rank</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Pelanggan</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total Pembelanjaan</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Jumlah Booking</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total Tiket</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Rata-rata/Booking</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($topUsers ?? [] as $index => $userStat)
                <tr class="hover:bg-gray-50 transition {{ $index < 3 ? 'bg-yellow-50' : '' }}">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            @if($index == 0)
                                <span class="text-2xl">🥇</span>
                            @elseif($index == 1)
                                <span class="text-2xl">🥈</span>
                            @elseif($index == 2)
                                <span class="text-2xl">🥉</span>
                            @else
                                <span class="text-lg font-bold text-gray-600">#{{ $index + 1 }}</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white font-bold">
                                {{ substr($userStat->user->name ?? 'U', 0, 1) }}
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $userStat->user->name ?? 'Unknown' }}</div>
                                <div class="text-sm text-gray-500">{{ $userStat->user->email ?? '-' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <div class="text-sm font-bold text-green-600">
                            Rp {{ number_format($userStat->total_spent, 0, ',', '.') }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <div class="text-sm font-semibold text-gray-900">
                            {{ number_format($userStat->total_bookings, 0, ',', '.') }} kali
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <div class="text-sm text-gray-600">
                            {{ number_format($userStat->total_tickets, 0, ',', '.') }} tiket
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <div class="text-sm text-gray-600">
                            Rp {{ number_format($userStat->total_bookings > 0 ? $userStat->total_spent / $userStat->total_bookings : 0, 0, ',', '.') }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        @if($userStat->total_spent >= 500000)
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gradient-to-r from-yellow-400 to-yellow-500 text-white">
                                ⭐ VIP
                            </span>
                        @elseif($userStat->total_spent >= 200000)
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                👑 Premium
                            </span>
                        @else
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                Regular
                            </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center">
                        <i data-lucide="user-x" class="w-16 h-16 mx-auto mb-4 text-gray-300"></i>
                        <p class="text-gray-500 font-medium">Belum ada data pelanggan untuk periode ini</p>
                        <p class="text-sm text-gray-400 mt-1">Coba ubah filter tanggal</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- GRAFIK DISTRIBUSI PELANGGAN --}}
@if(isset($topUsers) && $topUsers->isNotEmpty())
<div class="bg-white p-6 rounded-xl shadow-md">
    <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
        <i data-lucide="pie-chart" class="w-5 h-5 mr-2 text-indigo-600"></i>
        Top 10 Pelanggan Berdasarkan Pembelanjaan
    </h3>
    <canvas id="userChart" height="80"></canvas>
</div>

<script>
// Data untuk Chart
const userData = @json($topUsers->take(10) ?? []);
const userLabels = userData.map(u => u.user?.name || 'Unknown');
const userSpending = userData.map(u => u.total_spent);

// Buat Chart
const ctx = document.getElementById('userChart').getContext('2d');
new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: userLabels,
        datasets: [{
            label: 'Total Pembelanjaan',
            data: userSpending,
            backgroundColor: [
                'rgba(239, 68, 68, 0.8)',
                'rgba(249, 115, 22, 0.8)',
                'rgba(234, 179, 8, 0.8)',
                'rgba(34, 197, 94, 0.8)',
                'rgba(14, 165, 233, 0.8)',
                'rgba(99, 102, 241, 0.8)',
                'rgba(168, 85, 247, 0.8)',
                'rgba(236, 72, 153, 0.8)',
                'rgba(161, 161, 170, 0.8)',
                'rgba(148, 163, 184, 0.8)',
            ],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'right',
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.label + ': Rp ' + context.parsed.toLocaleString('id-ID');
                    }
                }
            }
        }
    }
});

lucide.createIcons();
</script>
@endif

<script>
lucide.createIcons();
</script>