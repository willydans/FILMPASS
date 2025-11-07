{{-- File: resources/views/admin/studio_edit.blade.php (BARU) --}}

@extends('layouts.admin')

@section('title', 'Edit Studio: ' . $studio->name)

@section('content')

    <div class="mb-4">
        <a href="{{ route('admin.studios.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-medium py-2 px-4 rounded-lg bg-indigo-50 hover:bg-indigo-100 transition shadow-sm">
            <i data-lucide="arrow-left" class="w-5 h-5 mr-2"></i>
            Kembali ke Manajemen Studio
        </a>
    </div>

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Edit Studio: {{ $studio->name }}</h1>
        <p class="text-gray-500">Perbarui detail studio di bawah ini.</p>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100 max-w-3xl">
        
        {{-- Form ini akan mengirim ke 'StudioController@update' --}}
        <form action="{{ route('admin.studios.update', $studio->id) }}" method="POST">
            @csrf           {{-- Token Keamanan Wajib --}}
            @method('PUT')   {{-- Wajib untuk Update Resource --}}

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Studio</label>
                        {{-- 'old()' untuk menyimpan data jika validasi gagal, '$studio->name' untuk data asli --}}
                        <input type="text" name="name" id="name" required
                               class="w-full border-gray-300 rounded-lg p-2 focus:ring-indigo-600 focus:border-indigo-600"
                               value="{{ old('name', $studio->name) }}">
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="type" class="block text-sm font-semibold text-gray-700 mb-2">Tipe Studio</label>
                        <input type="text" name="type" id="type" required
                               class="w-full border-gray-300 rounded-lg p-2 focus:ring-indigo-600 focus:border-indigo-600"
                               value="{{ old('type', $studio->type) }}">
                        @error('type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="capacity" class="block text-sm font-semibold text-gray-700 mb-2">Kapasitas Kursi</label>
                        <input type="number" name="capacity" id="capacity" required
                               class="w-full border-gray-300 rounded-lg p-2 focus:ring-indigo-600 focus:border-indigo-600"
                               value="{{ old('capacity', $studio->capacity) }}">
                        @error('capacity') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="base_price" class="block text-sm font-semibold text-gray-700 mb-2">Harga Tiket Dasar (Rp)</label>
                        <input type="number" name="base_price" id="base_price" required
                               class="w-full border-gray-300 rounded-lg p-2 focus:ring-indigo-600 focus:border-indigo-600"
                               value="{{ old('base_price', $studio->base_price) }}">
                        @error('base_price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                        <select name="status" id="status" required
                                class="w-full border-gray-300 rounded-lg p-2 focus:ring-indigo-600 focus:border-indigo-600">
                            <option value="Aktif" @if(old('status', $studio->status) == 'Aktif') selected @endif>Aktif</option>
                            <option value="Maintenance" @if(old('status', $studio->status) == 'Maintenance') selected @endif>Maintenance</option>
                        </select>
                        @error('status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
                        <textarea name="description" id="description" rows="5"
                                  class="w-full border-gray-300 rounded-lg p-2 focus:ring-indigo-600 focus:border-indigo-600"
                                  placeholder="Tulis deskripsi singkat studio...">{{ old('description', $studio->description) }}</textarea>
                        @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Fasilitas</label>
                        <div class="h-48 overflow-y-auto bg-gray-50 border border-gray-300 rounded-lg p-4 space-y-2">
                            @php
                                // Ambil ID fasilitas yang sudah dimiliki studio ini
                                $checkedFacilities = $studio->facilities->pluck('id')->toArray();
                            @endphp
                            
                            @forelse ($facilities as $facility)
                                <label class="flex items-center">
                                    <input type="checkbox" name="facilities[]" value="{{ $facility->id }}"
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                           {{-- Periksa apakah fasilitas ini harus di-check --}}
                                           @if(in_array($facility->id, old('facilities', $checkedFacilities))) checked @endif>
                                    <span class="ml-2 text-sm text-gray-700">{{ $facility->name }}</span>
                                </label>
                            @empty
                                <p class="text-gray-500 text-sm">Belum ada data fasilitas.</p>
                            @endforelse
                        </div>
                        @error('facilities') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="flex justify-end mt-6 border-t pt-6">
                <button type="submit" class="bg-indigo-700 hover:bg-indigo-800 text-white font-semibold py-2 px-6 rounded-lg shadow-md flex items-center transition duration-150">
                    <i data-lucide="save" class="w-5 h-5 mr-2"></i>
                    Simpan Perubahan
                </button>
            </div>
            
        </form>
    </div>
@endsection

@push('scripts')
<script>
    // Panggil lucide.createIcons() lagi untuk merender ikon
    lucide.createIcons();
</script>
@endpush