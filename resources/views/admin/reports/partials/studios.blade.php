<div class="space-y-8">

    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100">
        <h2 class="text-xl font-bold text-gray-800 mb-2">Laporan Pendapatan per Studio</h2>
        <p class="text-sm text-gray-600 mb-4">Analisis performa studio berdasarkan total pendapatan dan jumlah tiket yang terjual dalam periode {{ $startDate }} s/d {{ $endDate }}.</p>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            {{-- Statistik Ringkasan --}}
            <div class="space-y-2 border-r border-gray-100 pr-4">
                <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                    <span class="text-sm text-gray-600">Total Studio Terdaftar</span>
                    <span class="text-lg font-bold text-indigo-600">{{ $totalStudios }}</span>
                </div>
                <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                    <span class="text-sm text-gray-600">Total Tiket Terjual</span>
                    <span class="text-lg font-bold text-gray-900">{{ number_format($studioRevenue->sum('total_tickets')) }}</span>
                </div>
                <div class="flex justify-between items-center pt-2">
                    <span class="text-sm font-semibold text-gray-800">Total Pendapatan Bersih</span>
                    <span class="text-xl font-extrabold text-green-600">
                        Rp {{ number_format($studioRevenue->sum('total_revenue'), 0, ',', '.') }}
                    </span>
                </div>
            </div>

            {{-- Visualisasi Studio Terlaris (Sederhana) --}}
            <div class="space-y-3 md:col-span-2">
                <h4 class="text-md font-semibold text-gray-700">Studio dengan Pendapatan Tertinggi</h4>
                @php
                    $maxRevenue = $studioRevenue->max('total_revenue');
                @endphp
                @forelse($studioRevenue as $studio)
                    <div class="flex items-center text-sm">
                        <span class="w-2/5 truncate text-gray-600 font-medium">{{ $studio->name }} ({{ $studio->type }})</span>
                        <div class="flex-grow mx-2">
                            <div class="h-3 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full bg-blue-500" style="width: {{ ($studio->total_revenue / $maxRevenue) * 100 }}%;"></div>
                            </div>
                        </div>
                        <span class="w-1/4 text-right font-bold text-gray-800">Rp {{ number_format($studio->total_revenue, 0, ',', '.') }}</span>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm">Tidak ada data studio dalam periode ini.</p>
                @endforelse
            </div>
        </div>
    </div>
    
    {{-- Tabel Detail Studio --}}
    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
        <h3 class="text-lg font-bold p-4 bg-gray-50 border-b border-gray-200 text-gray-800">Detail Pendapatan per Studio</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-white">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Studio</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tipe</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total Tiket</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total Booking</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Pendapatan Bersih</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($studioRevenue as $studio)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-900">
                            {{ $studio->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            <span class="px-2 py-0.5 rounded text-xs font-semibold uppercase bg-indigo-100 text-indigo-700">
                                {{ $studio->type }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                            {{ number_format($studio->total_tickets) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ number_format($studio->total_bookings) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-700">
                            Rp {{ number_format($studio->total_revenue, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-10 text-gray-500">Tidak ada data pendapatan studio dalam periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>