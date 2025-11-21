<div class="space-y-8">

    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100">
        <h2 class="text-xl font-bold text-gray-800 mb-2">Laporan Film Terlaris</h2>
        <p class="text-sm text-gray-600 mb-4">Top 10 film berdasarkan pendapatan dalam periode {{ $startDate }} s/d {{ $endDate }}.</p>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            {{-- Statistik Ringkasan --}}
            <div class="space-y-2">
                <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                    <span class="text-sm text-gray-600">Total Film Aktif</span>
                    <span class="text-lg font-bold text-indigo-600">{{ $totalFilms }}</span>
                </div>
                <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                    <span class="text-sm text-gray-600">Total Transaksi Film Top</span>
                    <span class="text-lg font-bold text-gray-900">{{ $topFilms->sum('total_bookings') }}</span>
                </div>
                <div class="flex justify-between items-center pt-2">
                    <span class="text-sm font-semibold text-gray-800">Total Pendapatan (Top 10)</span>
                    <span class="text-xl font-extrabold text-green-600">
                        Rp {{ number_format($topFilms->sum('total_revenue'), 0, ',', '.') }}
                    </span>
                </div>
            </div>

            {{-- Visualisasi Bar Chart (Sederhana) --}}
            <div class="space-y-3">
                <h4 class="text-md font-semibold text-gray-700">Distribusi Penjualan Kursi</h4>
                @php
                    $maxTickets = $topFilms->max('total_tickets');
                @endphp
                @foreach($topFilms as $film)
                    <div class="flex items-center text-sm">
                        <span class="w-2/5 truncate text-gray-600">{{ $film->title }}</span>
                        <div class="flex-grow mx-2">
                            <div class="h-3 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full bg-indigo-500" style="width: {{ ($film->total_tickets / $maxTickets) * 100 }}%;"></div>
                            </div>
                        </div>
                        <span class="w-1/5 text-right font-bold text-gray-800">{{ $film->total_tickets }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    
    {{-- Tabel Detail Film Terlaris --}}
    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
        <h3 class="text-lg font-bold p-4 bg-gray-50 border-b border-gray-200 text-gray-800">Detail Top 10 Film</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-white">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Peringkat</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Film</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tiket Terjual</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total Booking</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Pendapatan Bersih</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($topFilms as $index => $film)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="font-extrabold text-lg text-indigo-700">{{ $index + 1 }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $film->title }}</div>
                            <div class="text-xs text-gray-500">{{ $film->schedule->film->rating ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                            {{ number_format($film->total_tickets) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ number_format($film->total_bookings) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-700">
                            Rp {{ number_format($film->total_revenue, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @if($topFilms->isEmpty())
            <div class="text-center py-10 text-gray-500">Tidak ada data film terlaris dalam periode ini.</div>
        @endif
        
    </div>
</div>