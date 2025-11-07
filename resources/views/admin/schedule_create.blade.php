{{-- File: resources/views/admin/schedule_create.blade.php --}}

@extends('layouts.admin')

@section('content')

    <div class="mb-4">
        <a href="{{ route('admin.schedules.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-medium py-2 px-4 rounded-lg bg-indigo-50 hover:bg-indigo-100 transition shadow-sm">
            <i data-lucide="arrow-left" class="w-5 h-5 mr-2"></i>
            Kembali ke Jadwal Tayang
        </a>
    </div>

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Tambah Jadwal Tayang Baru</h1>
        <p class="text-gray-500">Buat jadwal pemutaran film di studio yang dipilih.</p>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100 max-w-2xl">
        
        {{-- 
          CATATAN: Rute 'admin.schedules.store' sudah otomatis dibuat
          oleh Route::resource('/schedules', ...) di file web.php Anda.
        --}}
        <form action="{{ route('admin.schedules.store') }}" method="POST">
            @csrf {{-- Token Keamanan Wajib Laravel --}}

            <div class="mb-4">
                <label for="film_id" class="block text-sm font-semibold text-gray-700 mb-2">Pilih Film</label>
                <select name="film_id" id="film_id" required 
                        class="w-full border-gray-300 rounded-lg p-2 focus:ring-indigo-600 focus:border-indigo-600">
                    <option value="">-- Pilih salah satu film --</option>
                    @foreach ($films as $film)
                        <option value="{{ $film->id }}">{{ $film->title }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="studio_id" class="block text-sm font-semibold text-gray-700 mb-2">Pilih Studio</label>
                <select name="studio_id" id="studio_id" required 
                        class="w-full border-gray-300 rounded-lg p-2 focus:ring-indigo-600 focus:border-indigo-600">
                    <option value="">-- Pilih salah satu studio --</option>
                    @foreach ($studios as $studio)
                        <option value="{{ $studio->id }}">{{ $studio->name }} ({{ $studio->type }})</option>
                    @endforeach
                </select>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="start_time" class="block text-sm font-semibold text-gray-700 mb-2">Waktu Mulai</label>
                    <input type="datetime-local" name="start_time" id="start_time" required
                           class="w-full border-gray-300 rounded-lg p-2 focus:ring-indigo-600 focus:border-indigo-600">
                </div>
                <div>
                    <label for="end_time" class="block text-sm font-semibold text-gray-700 mb-2">Waktu Selesai</label>
                    <input type="datetime-local" name="end_time" id="end_time" required
                           class="w-full border-gray-300 rounded-lg p-2 focus:ring-indigo-600 focus:border-indigo-600">
                </div>
            </div>

            <div class="mb-6">
                <label for="price" class="block text-sm font-semibold text-gray-700 mb-2">Harga Tiket (Rp)</label>
                <input type="number" name="price" id="price" required placeholder="Contoh: 50000"
                       class="w-full border-gray-300 rounded-lg p-2 focus:ring-indigo-600 focus:border-indigo-600">
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-indigo-700 hover:bg-indigo-800 text-white font-semibold py-2 px-6 rounded-lg shadow-md flex items-center transition duration-150">
                    <i data-lucide="save" class="w-5 h-5 mr-2"></i>
                    Simpan Jadwal
                </button>
            </div>
            
        </form>
    </div>
@endsection

@push('scripts')
<script>
    // Panggil lucide.createIcons() lagi untuk merender ikon
    // yang baru ditambahkan (jika Anda memindahkannya ke file JS terpisah)
    lucide.createIcons();
</script>
@endpush