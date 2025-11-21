@extends('layouts.admin')

@section('title', 'Jadwal Tayang')

@section('content')

{{-- Notifikasi Success --}}
@if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-lg flex items-start">
        <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <div>
            <p class="font-semibold text-green-800">Berhasil!</p>
            <p class="text-green-700 text-sm">{{ session('success') }}</p>
        </div>
    </div>
@endif

{{-- Notifikasi Error --}}
@if($errors->any())
    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg">
        <div class="flex items-start">
            <svg class="w-5 h-5 text-red-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <div>
                <p class="font-semibold text-red-800 mb-1">Terjadi kesalahan:</p>
                <ul class="list-disc list-inside text-red-700 text-sm space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif

{{-- Bagian Header & Tombol Tambah --}}
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Jadwal Tayang</h1>
        <p class="text-gray-600">Kelola jadwal tayang film di semua studio</p>
    </div>
    <div>
        <a href="{{ route('admin.schedules.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2.5 rounded-lg inline-flex items-center shadow-md transition duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Tambah Jadwal
        </a>
    </div>
</div>

{{-- Bagian Filter --}}
<div class="bg-white p-4 rounded-lg shadow-md mb-6 border border-gray-200">
    <form action="{{ route('admin.schedules.index') }}" method="GET" class="flex flex-col md:flex-row md:items-center md:space-x-4 space-y-3 md:space-y-0">
        <label for="filter_tanggal" class="text-sm font-medium text-gray-700 whitespace-nowrap">Filter Tanggal:</label>
        <input type="date" 
               name="filter_tanggal" 
               id="filter_tanggal" 
               value="{{ $filterDate }}" 
               class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <button type="submit" 
                class="bg-blue-500 text-white px-4 py-2 text-sm rounded-lg hover:bg-blue-600 transition duration-200">
            Filter
        </button>
        <a href="{{ route('admin.schedules.index') }}" 
           class="text-gray-600 hover:text-gray-800 text-sm underline">
            Reset
        </a>
        
        <span class="text-gray-500 text-sm md:ml-auto">
            <span class="font-semibold text-gray-700">{{ $scheduleCount }}</span> jadwal ditemukan
        </span>
    </form>
</div>

{{-- Bagian Tabel Jadwal --}}
<div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
    {{-- Header Tabel --}}
    <div class="hidden lg:grid grid-cols-12 gap-4 px-6 py-4 border-b bg-gray-50 text-xs font-semibold text-gray-600 uppercase tracking-wider">
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
        {{-- Desktop View --}}
        <div class="hidden lg:grid grid-cols-12 gap-4 px-6 py-4 items-center {{ !$loop->last ? 'border-b border-gray-200' : '' }} hover:bg-gray-50 transition-colors">
            
            {{-- FILM --}}
            <div class="col-span-3">
                <div class="flex items-center space-x-3">
                    @if($schedule->film->poster_path)
                        <img src="{{ asset('storage/' . $schedule->film->poster_path) }}" 
                             alt="{{ $schedule->film->title }}" 
                             class="w-12 h-16 object-cover rounded shadow-sm">
                    @endif
                    <div>
                        <span class="text-sm font-semibold text-gray-800 block">{{ $schedule->film->title }}</span>
                        <span class="text-xs text-gray-500">{{ $schedule->film->duration_minutes }} menit</span>
                    </div>
                </div>
            </div>
            
            {{-- STUDIO --}}
            <div class="col-span-2">
                <span class="text-sm font-medium text-gray-800">{{ $schedule->studio->name }}</span>
                <span class="text-xs text-gray-500 block">{{ $schedule->studio->type }}</span>
            </div>
            
            {{-- WAKTU --}}
            <div class="col-span-2">
                <span class="text-sm font-medium text-gray-800 block">
                    {{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}
                </span>
                <span class="text-xs text-gray-500">
                    {{ $schedule->start_time->translatedFormat('d M Y') }}
                </span>
            </div>
            
            {{-- HARGA --}}
            <div class="col-span-1">
                <span class="text-sm font-semibold text-blue-600">Rp {{ number_format($schedule->price, 0, ',', '.') }}</span>
            </div>
            
            {{-- OKUPANSI --}}
            <div class="col-span-2">
                @php
                    $kursiTerjual = $schedule->bookings_sum_seat_count ?? 0;
                    $kapasitas = $schedule->studio->capacity;
                    $persentase = ($kapasitas > 0) ? ($kursiTerjual / $kapasitas) * 100 : 0;
                @endphp
                <div class="flex items-center justify-between text-sm mb-1">
                    <span class="text-gray-600 text-xs">{{ $kursiTerjual }}/{{ $kapasitas }}</span>
                    <span class="font-medium text-gray-800 text-xs">{{ round($persentase) }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="h-2 rounded-full {{ $persentase >= 80 ? 'bg-red-500' : ($persentase >= 50 ? 'bg-yellow-500' : 'bg-green-500') }}" 
                         style="width: {{ $persentase }}%"></div>
                </div>
            </div>
            
            {{-- STATUS --}}
            <div class="col-span-1">
                @if($schedule->status == 'sedang_tayang')
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-green-100 text-green-700">
                        Tayang
                    </span>
                @elseif($schedule->status == 'selesai')
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-gray-100 text-gray-700">
                        Selesai
                    </span>
                @else
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-blue-100 text-blue-700">
                        Terjadwal
                    </span>
                @endif
            </div>
            
            {{-- AKSI --}}
            <div class="col-span-1 flex justify-center space-x-2">
                <a href="{{ route('admin.schedules.edit', $schedule->id) }}" 
                   class="text-blue-500 hover:text-blue-700 transition-colors"
                   title="Edit">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </a>
                <form action="{{ route('admin.schedules.destroy', $schedule->id) }}" 
                      method="POST" 
                      class="inline"
                      onsubmit="return confirm('Yakin ingin menghapus jadwal ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="text-red-500 hover:text-red-700 transition-colors"
                            title="Hapus">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>

        {{-- Mobile View (Card) --}}
        <div class="lg:hidden p-4 {{ !$loop->last ? 'border-b border-gray-200' : '' }}">
            <div class="flex space-x-3 mb-3">
                @if($schedule->film->poster_path)
                    <img src="{{ asset('storage/' . $schedule->film->poster_path) }}" 
                         alt="{{ $schedule->film->title }}" 
                         class="w-16 h-20 object-cover rounded shadow-sm">
                @endif
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-800 mb-1">{{ $schedule->film->title }}</h3>
                    <p class="text-sm text-gray-600 mb-1">{{ $schedule->studio->name }} ({{ $schedule->studio->type }})</p>
                    <p class="text-sm text-gray-700 font-medium">
                        {{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}
                    </p>
                </div>
            </div>
            
            <div class="flex justify-between items-center mb-2">
                <span class="text-sm font-semibold text-blue-600">Rp {{ number_format($schedule->price, 0, ',', '.') }}</span>
                @php
                    $kursiTerjual = $schedule->bookings_sum_seat_count ?? 0;
                    $kapasitas = $schedule->studio->capacity;
                    $persentase = ($kapasitas > 0) ? ($kursiTerjual / $kapasitas) * 100 : 0;
                @endphp
                <span class="text-xs text-gray-600">{{ $kursiTerjual }}/{{ $kapasitas }} ({{ round($persentase) }}%)</span>
            </div>
            
            <div class="flex justify-between items-center">
                @if($schedule->status == 'sedang_tayang')
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-green-100 text-green-700">Tayang</span>
                @else
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-blue-100 text-blue-700">Terjadwal</span>
                @endif
                
                <div class="flex space-x-2">
                    <a href="{{ route('admin.schedules.edit', $schedule->id) }}" 
                       class="bg-blue-500 text-white px-3 py-1.5 rounded text-xs">
                        Edit
                    </a>
                    <form action="{{ route('admin.schedules.destroy', $schedule->id) }}" 
                          method="POST" 
                          class="inline"
                          onsubmit="return confirm('Yakin ingin menghapus?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white px-3 py-1.5 rounded text-xs">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        {{-- Jika tidak ada jadwal --}}
        <div class="text-center py-12">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <p class="text-gray-500 font-medium mb-2">Tidak ada jadwal ditemukan</p>
            <p class="text-gray-400 text-sm mb-4">Tidak ada jadwal tayang untuk tanggal {{ \Carbon\Carbon::parse($filterDate)->translatedFormat('d F Y') }}</p>
            <a href="{{ route('admin.schedules.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Tambah Jadwal Baru
            </a>
        </div>
    @endforelse
</div>

@endsection