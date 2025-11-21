@extends('layouts.admin')

@section('title', 'Edit Film')

@section('content')
<div class="max-w-3xl mx-auto">
    
    <div class="mb-8">
        <a href="{{ route('admin.films.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 mb-4">
            <i data-lucide="arrow-left" class="w-5 h-5 mr-2"></i>
            Kembali ke Daftar Film
        </a>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Edit Film</h1>
        <p class="text-gray-600">Perbarui informasi film: <span class="font-semibold">{{ $film->title }}</span></p>
    </div>

    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-8">
        
        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg">
                <div class="flex items-start">
                    <i data-lucide="alert-circle" class="w-5 h-5 text-red-500 mr-3 mt-0.5"></i>
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

        <form action="{{ route('admin.films.update', $film->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Judul Film -->
            <div>
                <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
                    Judul Film <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="title" 
                    name="title" 
                    value="{{ old('title', $film->title) }}"
                    required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('title') border-red-500 @enderror"
                    placeholder="Contoh: Spider-Man: No Way Home"
                >
            </div>

            <!-- Trailer URL (BARU) -->
            <div>
                <label for="trailer_url" class="block text-sm font-semibold text-gray-700 mb-2">
                    Link Trailer YouTube
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="youtube" class="h-5 w-5 text-gray-400"></i>
                    </div>
                    <input type="url" id="trailer_url" name="trailer_url" value="{{ old('trailer_url', $film->trailer_url) }}"
                        class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="https://www.youtube.com/watch?v=...">
                </div>
                <p class="text-xs text-gray-500 mt-1">Masukkan link lengkap video YouTube (opsional)</p>
            </div>

            <!-- Input Genre (Multi Select Checkbox) -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Genre <span class="text-red-500">*</span> <span class="text-xs font-normal text-gray-500">(Bisa pilih lebih dari satu)</span>
                </label>
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        @php
                            // 1. Daftar Master Genre
                            $genres = ['Action', 'Adventure', 'Animation', 'Comedy', 'Crime', 'Documentary', 'Drama', 'Family', 'Fantasy', 'Horror', 'Mystery', 'Romance', 'Sci-Fi', 'Thriller', 'War'];
                            
                            // 2. Ambil data lama (old) jika validasi gagal, ATAU ambil dari database
                            $selectedGenres = old('genre');
                            if (!$selectedGenres && $film->genre) {
                                $selectedGenres = explode(', ', $film->genre);
                            }
                            $selectedGenres = $selectedGenres ?? [];
                        @endphp
                        
                        @foreach($genres as $genre)
                            <label class="inline-flex items-center space-x-2 cursor-pointer hover:bg-gray-100 p-2 rounded transition">
                                <input type="checkbox" name="genre[]" value="{{ $genre }}" 
                                    class="rounded text-indigo-600 focus:ring-indigo-500 h-4 w-4 border-gray-300"
                                    {{ in_array($genre, $selectedGenres) ? 'checked' : '' }}>
                                <span class="text-sm text-gray-700">{{ $genre }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Poster Film -->
            <div>
                <label for="poster_file" class="block text-sm font-semibold text-gray-700 mb-2">
                    Poster Film
                </label>
                
                @if($film->poster_path)
                    <div class="mb-4 flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <img src="{{ asset('storage/' . $film->poster_path) }}" 
                             alt="{{ $film->title }}" 
                             class="w-16 h-24 object-cover rounded shadow-sm mr-4"
                             id="current-poster">
                        <div>
                            <p class="text-sm font-medium text-gray-700">Poster Saat Ini</p>
                            <p class="text-xs text-gray-500">Akan diganti jika Anda mengupload baru</p>
                        </div>
                    </div>
                @endif

                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-indigo-400 transition-colors @error('poster_file') border-red-500 @enderror">
                    <div class="space-y-1 text-center">
                        <i data-lucide="image" class="mx-auto h-12 w-12 text-gray-400"></i>
                        <div class="flex text-sm text-gray-600 justify-center">
                            <label for="poster_file" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none">
                                <span>Upload poster baru</span>
                                <input id="poster_file" name="poster_file" type="file" accept="image/*" class="sr-only" onchange="previewImage(event)">
                            </label>
                            <p class="pl-1">atau drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG, WEBP hingga 2MB</p>
                    </div>
                </div>
                
                <div id="preview-container" class="mt-4 hidden text-center">
                    <p class="text-sm text-gray-600 mb-2">Preview Poster Baru:</p>
                    <img id="preview-image" class="max-w-xs rounded-lg shadow-md mx-auto" alt="Preview poster baru">
                </div>
            </div>

            <!-- Deskripsi -->
            <div>
                <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                    Deskripsi Film
                </label>
                <textarea 
                    id="description" 
                    name="description" 
                    rows="4"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="Ceritakan tentang film ini..."
                >{{ old('description', $film->description) }}</textarea>
            </div>

            <!-- Durasi & Rating -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div>
                    <label for="duration_minutes" class="block text-sm font-semibold text-gray-700 mb-2">
                        Durasi (menit) <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="number" 
                        id="duration_minutes" 
                        name="duration_minutes" 
                        value="{{ old('duration_minutes', $film->duration_minutes) }}"
                        required
                        min="1"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('duration_minutes') border-red-500 @enderror"
                        placeholder="148"
                    >
                </div>

                <div>
                    <label for="rating" class="block text-sm font-semibold text-gray-700 mb-2">
                        Rating
                    </label>
                    <select 
                        id="rating" 
                        name="rating"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    >
                        <option value="">Pilih Rating</option>
                        <option value="SU" {{ old('rating', $film->rating) == 'SU' ? 'selected' : '' }}>SU - Semua Umur</option>
                        <option value="13+" {{ old('rating', $film->rating) == '13+' ? 'selected' : '' }}>13+ - Remaja</option>
                        <option value="17+" {{ old('rating', $film->rating) == '17+' ? 'selected' : '' }}>17+ - Dewasa</option>
                        <option value="21+" {{ old('rating', $film->rating) == '21+' ? 'selected' : '' }}>21+ - Dewasa</option>
                    </select>
                </div>
            </div>

            <!-- Tanggal Rilis -->
            <div>
                <label for="release_date" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Rilis</label>
                <input 
                    type="date" 
                    id="release_date" 
                    name="release_date" 
                    value="{{ old('release_date', $film->release_date ? $film->release_date->format('Y-m-d') : '') }}"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                >
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.films.index') }}" 
                   class="px-6 py-2.5 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition duration-200">
                    Batal
                </a>
                <button 
                    type="submit" 
                    class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-md transition duration-200">
                    Update Film
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    lucide.createIcons();

    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('preview-image');
                const container = document.getElementById('preview-container');
                preview.src = e.target.result;
                container.classList.remove('hidden');
                
                // Hide current poster when new one is selected
                const currentPoster = document.getElementById('current-poster');
                if (currentPoster) {
                    currentPoster.style.opacity = '0.3';
                }
            }
            reader.readAsDataURL(file);
        }
    }
</script>
@endpush
@endsection