@extends('layouts.admin')

@section('title', 'Edit Jadwal Tayang')

@section('content')
<div class="max-w-3xl mx-auto">
    
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('admin.schedules.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 mb-4">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Daftar Jadwal
        </a>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Edit Jadwal Tayang</h1>
        <p class="text-gray-600">Perbarui informasi jadwal pemutaran film</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-8">
        
        <!-- Error Messages -->
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

        <form action="{{ route('admin.schedules.update', $schedule->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Pilih Film -->
            <div>
                <label for="film_id" class="block text-sm font-semibold text-gray-700 mb-2">
                    Pilih Film <span class="text-red-500">*</span>
                </label>
                <select 
                    id="film_id" 
                    name="film_id" 
                    required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('film_id') border-red-500 @enderror"
                    onchange="updateDuration()"
                >
                    <option value="">-- Pilih Film --</option>
                    @foreach($films as $film)
                        <option value="{{ $film->id }}" 
                                data-duration="{{ $film->duration_minutes }}"
                                {{ old('film_id', $schedule->film_id) == $film->id ? 'selected' : '' }}>
                            {{ $film->title }} ({{ $film->duration_minutes }} menit)
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-500 mt-1" id="duration-info">Pilih film untuk melihat durasi</p>
            </div>

            <!-- Pilih Studio -->
            <div>
                <label for="studio_id" class="block text-sm font-semibold text-gray-700 mb-2">
                    Pilih Studio <span class="text-red-500">*</span>
                </label>
                <select 
                    id="studio_id" 
                    name="studio_id" 
                    required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('studio_id') border-red-500 @enderror"
                >
                    <option value="">-- Pilih Studio --</option>
                    @foreach($studios as $studio)
                        <option value="{{ $studio->id }}" {{ old('studio_id', $schedule->studio_id) == $studio->id ? 'selected' : '' }}>
                            {{ $studio->name }} (Kapasitas: {{ $studio->capacity }} kursi)
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Waktu Mulai -->
            <div>
                <label for="start_time" class="block text-sm font-semibold text-gray-700 mb-2">
                    Waktu Mulai <span class="text-red-500">*</span>
                </label>
                <input 
                    type="datetime-local" 
                    id="start_time" 
                    name="start_time" 
                    {{-- Format DateTime Local harus Y-m-d\TH:i --}}
                    value="{{ old('start_time', \Carbon\Carbon::parse($schedule->start_time)->format('Y-m-d\TH:i')) }}"
                    required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('start_time') border-red-500 @enderror"
                    onchange="calculateEndTime()"
                >
            </div>

            <!-- Info Waktu Selesai (Otomatis) -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <p class="font-semibold text-blue-800 text-sm">Waktu Selesai (Otomatis)</p>
                        <p class="text-blue-700 text-sm mt-1" id="end-time-display">
                            Akan dihitung otomatis berdasarkan durasi film
                        </p>
                    </div>
                </div>
            </div>

            <!-- Harga Tiket -->
            <div>
                <label for="price" class="block text-sm font-semibold text-gray-700 mb-2">
                    Harga Tiket <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-4 top-3 text-gray-500">Rp</span>
                    <input 
                        type="number" 
                        id="price" 
                        name="price" 
                        value="{{ old('price', $schedule->price) }}"
                        required
                        min="0"
                        step="1000"
                        class="w-full pl-12 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('price') border-red-500 @enderror"
                        placeholder="35000"
                    >
                </div>
                <p class="text-xs text-gray-500 mt-1">Harga dalam Rupiah (kelipatan 1.000)</p>
            </div>

            <!-- Status (Opsional - Karena di migrasi ada kolom status) -->
            <div>
                <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                    Status Penayangan
                </label>
                <select 
                    id="status" 
                    name="status" 
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                >
                    <option value="terjadwal" {{ old('status', $schedule->status) == 'terjadwal' ? 'selected' : '' }}>Terjadwal</option>
                    <option value="sedang_tayang" {{ old('status', $schedule->status) == 'sedang_tayang' ? 'selected' : '' }}>Sedang Tayang</option>
                    <option value="selesai" {{ old('status', $schedule->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.schedules.index') }}" 
                   class="px-6 py-2.5 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition duration-200">
                    Batal
                </a>
                <button 
                    type="submit" 
                    class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-md transition duration-200">
                    Update Jadwal
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function updateDuration() {
        const filmSelect = document.getElementById('film_id');
        const selectedOption = filmSelect.options[filmSelect.selectedIndex];
        const duration = selectedOption.getAttribute('data-duration');
        const durationInfo = document.getElementById('duration-info');
        
        if (duration) {
            durationInfo.textContent = `Durasi film: ${duration} menit`;
            durationInfo.classList.remove('text-gray-500');
            durationInfo.classList.add('text-indigo-600', 'font-semibold');
            calculateEndTime();
        } else {
            durationInfo.textContent = 'Pilih film untuk melihat durasi';
            durationInfo.classList.add('text-gray-500');
            durationInfo.classList.remove('text-indigo-600', 'font-semibold');
        }
    }

    function calculateEndTime() {
        const filmSelect = document.getElementById('film_id');
        const startTimeInput = document.getElementById('start_time');
        const endTimeDisplay = document.getElementById('end-time-display');
        
        const selectedOption = filmSelect.options[filmSelect.selectedIndex];
        // Pastikan duration ada, jika tidak set 0
        const duration = parseInt(selectedOption.getAttribute('data-duration')) || 0;
        const startTime = startTimeInput.value;
        
        if (duration && startTime) {
            const start = new Date(startTime);
            const end = new Date(start.getTime() + duration * 60000); // Add minutes in milliseconds
            
            const options = { 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric', 
                hour: '2-digit', 
                minute: '2-digit' 
            };
            const endTimeFormatted = end.toLocaleDateString('id-ID', options);
            
            endTimeDisplay.textContent = `Film akan selesai pada: ${endTimeFormatted}`;
            endTimeDisplay.classList.remove('text-blue-700');
            endTimeDisplay.classList.add('text-blue-900', 'font-semibold');
        } else {
            endTimeDisplay.textContent = 'Akan dihitung otomatis berdasarkan durasi film';
            endTimeDisplay.classList.add('text-blue-700');
            endTimeDisplay.classList.remove('text-blue-900', 'font-semibold');
        }
    }

    // Jalankan updateDuration saat halaman dimuat untuk mengisi info awal
    document.addEventListener('DOMContentLoaded', function() {
        updateDuration();
    });
</script>
@endsection