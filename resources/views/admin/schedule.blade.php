@extends('layouts.admin')

@section('content')

{{-- Bagian Header & Tombol Tambah --}}
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Jadwal Tayang</h1>
        <p class="text-gray-600">Kelola jadwal tayang film di semua studio</p>
    </div>
    <div>
        {{-- 
          CATATAN: Pastikan nama rute ini benar. 
          Berdasarkan 'Route::resource', seharusnya 'admin.schedules.create' (plural) 
        --}}
        <a href="{{ route('admin.schedules.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2.5 rounded-lg inline-flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Tambah Jadwal
        </a>
    </div>
</div>

{{-- Bagian Filter --}}
<div class="bg-white p-4 rounded-lg shadow-md mb-6">
    <form action="{{ route('admin.schedules.index') }}" method="GET" class="flex items-center space-x-4">
        <label for="filter_tanggal" class="text-sm font-medium text-gray-700">Filter Tanggal:</label>
        <input type="date" name="filter_tanggal" id="filter_tanggal" 
               value="{{ $filterDate }}" 
               class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 text-sm rounded-lg hover:bg-blue-600">Filter</button>
        
        <span class="text-gray-500 text-sm ml-auto">
            {{ $scheduleCount }} jadwal ditemukan
        </span>
    </form>
</div>

{{-- Bagian Tabel Jadwal --}}
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    {{-- Header Tabel --}}
    <div class="grid grid-cols-12 gap-4 px-6 py-4 border-b bg-gray-50 text-xs font-semibold text-gray-600 uppercase tracking-wider">
        <div class="col-span-3">Film</div>
        <div class="col-span-2">Studio</div>
        <div class="col-span-2">Waktu</div>
        <div class="col-span-1">Harga</div>
        <div class="col-span-2">Okupansi</div>
        <div class="col-span-1">Status</div>
        <div class="col-span-1 text-center">Aksi</div>
    </div>

    {{-- Body Tabel (Looping Data) --}}
    @forelse ($schedules as $schedule)
        <div class="grid grid-cols-12 gap-4 px-6 py-4 items-center {{ !$loop->last ? 'border-b border-gray-200' : '' }}">
            
            {{-- FILM --}}
            <div class="col-span-3">
                <span class="text-sm font-semibold text-gray-800">{{ $schedule->film->title }}</span>
            </div>
            
            {{-- STUDIO --}}
            <div class="col-span-2">
                <span class="text-sm text-gray-700">{{ $schedule->studio->name }} ({{ $schedule->studio->type }})</span>
            </div>
            
            {{-- WAKTU (PERBAIKAN DI SINI) --}}
            <div class="col-span-2">
                <span class="text-sm text-gray-700">
                    {{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}
                </span> {{-- Huruf 's' sudah dihapus dari sini --}}
            </div>
            
            {{-- HARGA (Format Angka) --}}
            <div class="col-span-1">
                <span class="text-sm font-medium text-blue-600">Rp {{ number_format($schedule->price, 0, ',', '.') }}</span>
            </div>
            
            {{-- OKUPANSI (Perhitungan) --}}
            <div class="col-span-2">
                @php
                    $kursiTerjual = $schedule->bookings_sum_seat_count ?? 0;
                    $kapasitas = $schedule->studio->capacity;
                    $persentase = ($kapasitas > 0) ? ($kursiTerjual / $kapasitas) * 100 : 0;
                @endphp
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">{{ $kursiTerjual }}/{{ $kapasitas }} kursi</span>
                    <span class="font-medium text-gray-800">{{ round($persentase) }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                    <div class="bg-yellow-500 h-2 rounded-full" style="width: {{ $persentase }}%"></div>
                </div>
            </div>
            
            {{-- STATUS (Conditional Badge) --}}
            <div class="col-span-1">
                @if ($schedule->status == 'Sedang Tayang')
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-green-100 text-green-700">
                        Sedang Tayang
                    </span>
                @else
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-blue-100 text-blue-700">
                        {{ $schedule->status }}
                    </span>
                @endif
            </div>
            
            {{-- AKSI --}}
            <div class="col-span-1 flex justify-center space-x-2">
                {{-- 
                  CATATAN: Nanti ubah rute ini ke 'admin.schedules.edit' 
                  dan 'admin.schedules.destroy'
                --}}
                <a href="{{ route('admin.schedules.edit', $schedule->id) }}" class="text-blue-500 hover:text-blue-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </a>
                <a href="#" class="text-red-500 hover:text-red-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </a>
            </div>
            
        </div>
    @empty
        {{-- Jika tidak ada jadwal ditemukan --}}
        <div class="text-center text-gray-500 p-6">
            Tidak ada jadwal tayang ditemukan untuk tanggal ini.
        </div>
    @endforelse
</div>

@endsection