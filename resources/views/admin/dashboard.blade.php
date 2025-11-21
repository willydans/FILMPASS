@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard Admin</h1>
        <p class="text-gray-600">Selamat datang di panel admin FilmPass</p>
    </div>

    <div id="stats-cards" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    </div>

    <!-- Tabel Manajemen User -->
    <div class="bg-white rounded-xl shadow-md border border-gray-100 mb-8">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-xl font-semibold text-gray-800">Manajemen Pengguna</h3>
            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i data-lucide="user-plus" class="w-4 h-4 mr-2"></i>
                Tambah User
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 200px;">Ubah Role</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($users as $user)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->role == 'admin')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Admin</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">User</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <form action="{{ route('admin.users.updateRole', $user) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <div class="flex items-center">
                                        <select name="role" onchange="this.form.submit()" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" {{ Auth::id() == $user->id ? 'disabled' : '' }}>
                                            @if ($user->role == 'admin')
                                                <option value="admin" selected>Admin (Saat Ini)</option>
                                                <option value="user">Ubah ke User</option>
                                            @else {{-- user --}}
                                                <option value="user" selected>User (Saat Ini)</option>
                                                <option value="admin">Ubah ke Admin</option>
                                            @endif
                                        </select>
                                        {{-- Tombol Simpan dihapus --}}
                                    </div>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                                Tidak ada pengguna yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
        <div class="p-6 bg-white border-t border-gray-200">
            {{ $users->links() }}
        </div>
        @endif
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
                <a href="{{ route('admin.bookings.index') }}" class="flex items-center p-3 rounded-lg transition duration-150">
                    <i data-lucide="ticket" class="w-5 h-5 mr-3"></i>
                    Pemesanan
                </a>
                <a href="{{ route('admin.reports.index') }}" class="flex items-center p-3 rounded-lg transition duration-150">
                    <i data-lucide="bar-chart-2" class="w-5 h-5 mr-3"></i>
                    Laporan
                </a>
            </div>
        </div>
    </div>
@endsection

@push('dashboard_scripts')
<script>
    /* Mengambil data dinamis dari DashboardController */
    const totalFilm = {{ $totalFilm ?? 0 }};
    const totalPemesanan = {{ $totalPemesanan ?? 0 }};
    const totalPendapatan = {{ $totalPendapatan ?? 0 }};
    const totalPengguna = {{ $totalPengguna ?? 0 }};
    const penjualanMingguanData = @json($penjualanMingguan ?? []);

    // 1. Render Kartu Statistik
    function renderStatsCards() {
        const container = document.getElementById('stats-cards');
        if (!container) return;

        const statsData = [
            { title: "Total Film", value: totalFilm, icon: "clapperboard" },
            { title: "Total Pemesanan", value: totalPemesanan, icon: "shopping-cart" },
            { title: "Total Pendapatan", value: totalPendapatan, icon: "wallet", isCurrency: true },
            { title: "Total Pengguna", value: totalPengguna, icon: "users" },
        ];
        let html = '';
        statsData.forEach(stat => {
            const displayValue = stat.isCurrency 
                ? formatRupiah(stat.value).replace('IDR', 'Rp')
                : (stat.value ? stat.value.toLocaleString('id-ID') : '0');
            
            const cardHtml = `
                <div class="bg-white p-5 rounded-xl shadow-md border border-gray-100 flex items-center justify-between transition-shadow duration-300 hover:shadow-lg">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">${stat.title}</p>
                        <p class="text-3xl font-bold text-gray-900">${displayValue}</p>
                    </div>
                    <div class="icon-box bg-indigo-100 text-indigo-600">
                        <i data-lucide="${stat.icon}" class="w-6 h-6"></i>
                    </div>
                </div>
            `;
                            html += cardHtml;
                        });
                        container.innerHTML = html;
                        lucide.createIcons(); // Call createIcons after injecting HTML
                    }
    // 2. Render Chart Penjualan Mingguan
    function renderSalesChart() {
        const container = document.getElementById('sales-chart');
        if (!container) return;

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
                        lucide.createIcons(); // Call createIcons after injecting HTML
                    }</script>
@endpush