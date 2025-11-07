@extends('layouts.admin')

@section('title', 'Manajemen Studio')

@section('content')

{{-- Inisialisasi Alpine.js untuk Modal --}}
<div x-data="{ showFacilityModal: false }">

    {{-- 
      CATATAN: 
      Blok "Kembali ke Dashboard" tidak ada di screenshot baru Anda, 
      jadi saya akan menyembunyikannya (beri komentar) agar "sama persis".
    --}}
    {{-- <div class="mb-4">
        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-indigo-600 ...">
            <i data-lucide="arrow-left" class="w-5 h-5 mr-2"></i>
            Kembali ke Dashboard
        </a>
    </div> --}}

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Manajemen Studio</h1>
            <p class="text-gray-500">Kelola studio bioskop dan fasilitas</p>
        </div>
        <div>
            {{-- Tombol "Tambah Studio" (sekarang di kanan atas) --}}
            <a href="{{ route('admin.studios.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md flex items-center w-full md:w-auto transition duration-150">
                <i data-lucide="plus" class="w-5 h-5 mr-2"></i>
                Tambah Studio
            </a>
        </div>
    </div>

    {{-- Tampilkan pesan sukses/error --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 space-y-4 md:space-y-0 p-4 bg-white rounded-lg shadow-sm">
        
        <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4 w-full md:w-auto">
            <div class="relative w-full sm:w-80">
                <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2"></i>
                <input type="text" id="search-studio" placeholder="Cari studio..." 
                       class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg w-full focus:ring-blue-600 focus:border-blue-600">
            </div>
            <div class="relative">
                <select id="filter-tipe" class="appearance-none block w-full bg-white border border-gray-300 py-2 pl-4 pr-8 rounded-lg leading-tight focus:outline-none focus:bg-white focus:border-blue-600 cursor-pointer">
                    <option value="Semua">Semua Tipe</option>
                    @foreach ($studios->pluck('type')->unique() as $type)
                        <option value="{{ $type }}">{{ $type }}</option>
                    @endforeach
                </select>
                <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
            </div>
        </div>

        <div class="flex space-x-3">
            <button 
                @click="showFacilityModal = true"
                class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md flex items-center w-full md:w-auto transition duration-150">
                <i data-lucide="sparkles" class="w-5 h-5 mr-2"></i>
                Kelola Fasilitas
            </button>
        </div>
    </div>

    <div id="studio-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        
        @forelse ($studios as $studio)
            <div data-id="{{ $studio->id }}" 
                 data-type="{{ $studio->type }}" 
                 data-name="{{ strtolower($studio->name) }} {{ strtolower($studio->description) }}"
                 class="studio-card bg-white p-5 rounded-lg shadow-md border border-gray-100 flex flex-col justify-between">
                
                <div>
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center space-x-3">
                            {{-- Ikon Studio Baru --}}
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <i data-lucide="monitor" class="w-5 h-5 text-blue-600"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">{{ $studio->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $studio->type }}</p>
                            </div>
                        </div>
                        {{-- Badge Status --}}
                        @if ($studio->status == 'Aktif')
                            <span class="text-xs font-semibold py-1 px-2 rounded-full bg-green-100 text-green-700">Aktif</span>
                        @else
                            <span class="text-xs font-semibold py-1 px-2 rounded-full bg-yellow-100 text-yellow-700">Maintenance</span>
                        @endif
                    </div>

                    <div class="space-y-3 mb-4 text-gray-700">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Kapasitas</span>
                            <span class="font-semibold text-gray-900">{{ $studio->capacity }} kursi</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Harga Tiket</span>
                            <span class="font-semibold text-blue-600">Rp {{ number_format($studio->base_price, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Okupansi</span>
                            <span class="font-semibold text-gray-900">0%</span> {{-- Data ini perlu kalkulasi, placeholder 0% --}}
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="flex justify-between text-xs mb-1">
                            <span class="text-gray-500">Tingkat Okupansi</span>
                            <span class="font-medium text-gray-600">78%</span> {{-- Data ini perlu kalkulasi, placeholder 78% --}}
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-1.5">
                            {{-- Warna diubah ke bg-yellow-500 --}}
                            <div class="bg-yellow-500 h-1.5 rounded-full" style="width: 78%"></div> 
                        </div>
                    </div>

                    <p class="text-xs text-gray-500 mb-4">
                        {{ $studio->description ?? 'Tidak ada deskripsi.' }}
                    </p>
                    
                    <div>
                        <h4 class="text-sm font-semibold mb-2 text-gray-800">Fasilitas</h4>
                        <div class="flex flex-wrap gap-2">
                            @forelse ($studio->facilities->take(3) as $facility) {{-- Ambil 3 fasilitas pertama --}}
                                <span class="text-xs text-gray-600 bg-gray-100 py-1 px-2 rounded">{{ $facility->name }}</span>
                            @empty
                                <span class="text-xs text-gray-400">Tidak ada fasilitas.</span>
                            @endforelse
                            {{-- Tampilkan "+1 lainnya" jika fasilitas lebih dari 3 --}}
                            @if ($studio->facilities->count() > 3)
                                <span class="text-xs text-gray-600 bg-gray-100 py-1 px-2 rounded">
                                    +{{ $studio->facilities->count() - 3 }} lainnya
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="flex justify-start space-x-2 items-center mt-6 border-t pt-4">
                    {{-- Tombol Edit --}}
                    <a href="{{ route('admin.studios.edit', $studio->id) }}" class="text-sm text-blue-600 bg-blue-50 hover:bg-blue-100 font-medium py-1.5 px-3 rounded-md flex items-center transition">
                        <i data-lucide="square-pen" class="w-4 h-4 mr-1"></i>
                        Edit
                    </a>
                    
                    @if ($studio->status == 'Aktif')
                        {{-- Tombol Maintenance --}}
                        <button class="text-sm text-yellow-600 bg-yellow-50 hover:bg-yellow-100 font-medium py-1.5 px-3 rounded-md flex items-center transition">
                            <i data-lucide="wrench" class="w-4 h-4 mr-1"></i>
                            Maintenance
                        </button>
                    @else
                        {{-- Tombol Aktifkan --}}
                        <button class="text-sm text-green-600 bg-green-50 hover:bg-green-100 font-medium py-1.5 px-3 rounded-md flex items-center transition">
                            <i data-lucide="play" class="w-4 h-4 mr-1"></i>
                            Aktifkan
                        </button>
                    @endif
                    
                    {{-- Tombol Hapus --}}
                    <form action="{{ route('admin.studios.destroy', $studio->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus studio ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-sm text-red-600 bg-red-50 hover:bg-red-100 font-medium py-1.5 px-3 rounded-md flex items-center transition">
                            <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i>
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <p class="col-span-full text-center text-gray-500 py-10">
                Belum ada studio yang ditambahkan.
                <a href="{{ route('admin.studios.create') }}" class="text-blue-600 hover:underline">Tambah Studio Baru</a>
            </p>
        @endforelse
    </div>
    
    
    {{-- MODAL UNTUK MENGELOLA FASILITAS (Tidak Berubah) --}}
    <div x-show="showFacilityModal" 
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
         @click.away="showFacilityModal = false"
         style="display: none;">
         
        <div class="bg-white p-6 rounded-xl shadow-2xl w-full max-w-2xl">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold text-gray-800">Kelola Fasilitas</h2>
                <button @click="showFacilityModal = false" class="text-gray-500 hover:text-gray-800">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Tambah Fasilitas Baru</h3>
                    <form action="{{ route('admin.facilities.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="facility_name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Fasilitas</label>
                            <input type="text" name="name" id="facility_name" required 
                                   placeholder="Contoh: Kursi Pijat"
                                   class="w-full border-gray-300 rounded-lg p-2 focus:ring-indigo-600 focus:border-indigo-600">
                            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <button type="submit" class="w-full bg-indigo-700 hover:bg-indigo-800 text-white font-semibold py-2 px-4 rounded-lg shadow-md flex items-center justify-center transition duration-150">
                            <i data-lucide="plus" class="w-5 h-5 mr-2"></i>
                            Tambah
                        </button>
                    </form>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Fasilitas Saat Ini</h3>
                    <div class="h-64 overflow-y-auto bg-gray-50 border border-gray-300 rounded-lg p-4 space-y-2">
                        @forelse ($facilities as $facility)
                            <div class="flex justify-between items-center">
                                <span class="text-gray-800">{{ $facility->name }}</span>
                                <form action="{{ route('admin.facilities.destroy', $facility->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus fasilitas ini? Ini akan menghapusnya dari semua studio.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        @empty
                            <p class="text-gray-500 text-sm">Belum ada fasilitas.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div> {{-- End div x-data --}}
    
@endsection

@push('scripts')
<script>
    // Pastikan ikon dirender setelah Blade selesai
    lucide.createIcons();

    // Fungsi filter sederhana (client-side)
    function filterStudios() {
        const searchQuery = document.getElementById('search-studio').value.toLowerCase();
        const filterType = document.getElementById('filter-tipe').value;
        const studioCards = document.querySelectorAll('.studio-card');

        studioCards.forEach(card => {
            const nameDesc = card.getAttribute('data-name');
            const type = card.getAttribute('data-type');

            const matchesSearch = nameDesc.includes(searchQuery);
            const matchesType = (filterType === 'Semua') || (type === filterType);

            if (matchesSearch && matchesType) {
                card.style.display = 'flex'; // 'flex' karena kartu kita menggunakan flexbox
            } else {
                card.style.display = 'none';
            }
        });
    }

    // Pasang listener ke input
    document.getElementById('search-studio').addEventListener('input', filterStudios);
    document.getElementById('filter-tipe').addEventListener('change', filterStudios);
</script>
@endpush