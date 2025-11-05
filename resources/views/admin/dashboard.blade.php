{{-- 1. Gunakan layout admin --}}
@extends('layouts.admin')

{{-- 2. Definisikan konten --}}
@section('content')

    <h1 class="text-3xl font-bold text-gray-800">Dashboard Admin</h1>
    <p class="text-gray-600 mb-6">Selamat datang di panel admin FilmPass</p>

    {{-- 3. KARTU STATISTIK --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        
        {{-- Total Film --}}
        <div class="bg-white p-5 rounded-lg shadow-md flex items-center justify-between">
            <div>
                <span class="text-sm font-medium text-gray-500">Total Film</span>
                <p class="text-3xl font-bold text-gray-800">{{ $totalFilm }}</p>
            </div>
            <div class="bg-blue-100 p-3 rounded-full">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"></path></svg>
            </div>
        </div>

        {{-- Total Pemesanan --}}
        <div class="bg-white p-5 rounded-lg shadow-md flex items-center justify-between">
            <div>
                <span class="text-sm font-medium text-gray-500">Total Pemesanan</span>
                <p class="text-3xl font-bold text-gray-800">{{ $totalPemesanan }}</p>
            </div>
            <div class="bg-green-100 p-3 rounded-full">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
        </div>

        {{-- Total Pendapatan --}}
        <div class="bg-white p-5 rounded-lg shadow-md flex items-center justify-between">
            <div>
                <span class="text-sm font-medium text-gray-500">Total Pendapatan</span>
                <p class="text-3xl font-bold text-gray-800">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
            </div>
            <div class="bg-yellow-100 p-3 rounded-full">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        {{-- Total Pengguna --}}
        <div class="bg-white p-5 rounded-lg shadow-md flex items-center justify-between">
            <div>
                <span class="text-sm font-medium text-gray-500">Total Pengguna</span>
                <p class="text-3xl font-bold text-gray-800">{{ $totalPengguna }}</p>
            </div>
            <div class="bg-purple-100 p-3 rounded-full">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
        </div>
    </div>

    {{-- 4. KONTEN TENGAH (PENJUALAN & AKSI CEPAT) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Penjualan Mingguan (Kiri) --}}
        <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Penjualan Mingguan</h3>
            <div class="space-y-4">
                @forelse ($penjualanMingguan as $penjualan)
                <div class="flex items-center">
                    <span class="w-10 text-sm font-medium text-gray-600">{{ $penjualan['hari'] }}</span>
                    <div class="flex-1 bg-gray-200 rounded-full h-6 mr-4">
                        <div class="bg-blue-500 h-6 rounded-full" 
                             style="width: {{ $penjualan['persentase'] }}%">
                        </div>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Rp {{ number_format($penjualan['total'], 0, ',', '.') }}</span>
                </div>
                @empty
                <p class="text-gray-500">Belum ada data penjualan minggu ini.</p>
                @endforelse
            </div>
        </div>

        {{-- Aksi Cepat (Kanan) --}}
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Aksi Cepat</h3>
            <div class="space-y-3">
                <a href="{{ route('admin.films.create') }}" class="block text-center w-full px-4 py-3 bg-blue-100 text-blue-700 font-medium rounded-lg hover:bg-blue-200 transition-colors">
                    + Tambah Film Baru
                </a>
                <a href="#" class="block text-center w-full px-4 py-3 bg-green-100 text-green-700 font-medium rounded-lg hover:bg-green-200 transition-colors">
                    + Buat Jadwal Tayang
                </a>
                <a href="#" class="block text-center w-full px-4 py-3 bg-yellow-100 text-yellow-700 font-medium rounded-lg hover:bg-yellow-200 transition-colors">
                    + Tambah Studio
                </a>
                <a href="#" class="block text-center w-full px-4 py-3 bg-purple-100 text-purple-700 font-medium rounded-lg hover:bg-purple-200 transition-colors">
                    Lihat Laporan
                </a>
            </div>
        </div>
    </div>

@endsection