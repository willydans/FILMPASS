{{-- RINGKASAN PENJUALAN --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    
    <div class="bg-gradient-to-br from-green-500 to-green-600 text-white p-8 rounded-xl shadow-lg">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-white/20 p-3 rounded-full">
                <i data-lucide="dollar-sign" class="w-8 h-8"></i>
            </div>
            <span class="text-sm font-medium bg-white/20 px-3 py-1 rounded-full">Total</span>
        </div>
        <h3 class="text-lg font-medium mb-2 opacity-90">Total Pendapatan</h3>
        <p class="text-4xl font-bold mb-4">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</p>
        <p class="text-sm opacity-80">{{ $startDate }} - {{ $endDate }}</p>
    </div>

    <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-8 rounded-xl shadow-lg">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-white/20 p-3 rounded-full">
                <i data-lucide="shopping-bag" class="w-8 h-8"></i>
            </div>
            <span class="text-sm font-medium bg-white/20 px-3 py-1 rounded-full">Transaksi</span>
        </div>
        <h3 class="text-lg font-medium mb-2 opacity-90">Total Pemesanan</h3>
        <p class="text-4xl font-bold mb-4">{{ number_format($totalBookings ?? 0, 0, ',', '.') }}</p>
        <p class="text-sm opacity-80">Semua status pemesanan</p>
    </div>
</div>

{{-- TABEL DETAIL PENJUALAN --}}
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="p-6 border-b border-gray-200">
        <h3 class="text-xl font-bold text-gray-900 flex items-center">
            <i data-lucide="receipt" class="w-5 h-5 mr-2 text-indigo-600"></i>
            Detail Transaksi Penjualan
        </h3>
        <p class="text-sm text-gray-600 mt-1">Daftar lengkap semua pemesanan tiket</p>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Film</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Studio</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tiket</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($salesData ?? [] as $booking)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        #{{ $booking->id }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $booking->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        <div class="font-medium">{{ $booking->schedule->film->title ?? '-' }}</div>
                        <div class="text-xs text-gray-500">{{ $booking->schedule->start_time->format('d M Y, H:i') }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $booking->schedule->studio->name ?? '-' }}
                        <span class="text-xs text-gray-500">({{ $booking->schedule->studio->type ?? '-' }})</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $booking->user->name ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">
                        {{ $booking->seat_count }} kursi
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-600">
                        Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($booking->booking_status == 'confirmed')
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Confirmed
                            </span>
                        @elseif($booking->booking_status == 'pending')
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Pending
                            </span>
                        @else
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Cancelled
                            </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center">
                        <i data-lucide="inbox" class="w-16 h-16 mx-auto mb-4 text-gray-300"></i>
                        <p class="text-gray-500 font-medium">Belum ada data penjualan untuk periode ini</p>
                        <p class="text-sm text-gray-400 mt-1">Coba ubah filter tanggal</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    @if(isset($salesData) && $salesData->hasPages())
    <div class="p-6 border-t border-gray-200">
        {{ $salesData->appends(request()->query())->links() }}
    </div>
    @endif
</div>

<script>
lucide.createIcons();
</script>