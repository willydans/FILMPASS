@extends('layouts.admin')

@section('title', 'Edit Film')

@section('content')
{{-- Menambahkan flex, items-center, dan justify-center untuk centering vertikal dan horizontal --}}
<div class="p-4 sm:p-6 bg-gray-50 min-h-screen flex items-center justify-center">
    {{-- Container utama berada di tengah. Menambahkan my-8 agar ada ruang di bagian atas/bawah saat layar penuh. --}}
    <div class="w-full max-w-5xl bg-white p-6 sm:p-10 rounded-2xl shadow-xl my-8">

        {{-- HEADER --}}
        <div class="mb-8 border-b border-gray-100 pb-4">
            <a href="{{ route('admin.films.index') }}" class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-800 transition duration-150">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                Kembali ke Manajemen Film
            </a>
            <h1 class="text-3xl font-extrabold text-gray-900 mt-2">Edit Film: <span class="text-indigo-600">{{ $film->title }}</span></h1>
            <p class="text-gray-500 mt-1">Perbarui detail film dan poster di bawah ini. Pastikan semua informasi akurat.</p>
        </div>

        {{-- FORM --}}
        <form action="{{ route('admin.films.update', $film->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Kolom Kiri: Detail Teks --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Judul Film --}}
                    <div>
                        <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">Judul Film <span class="text-red-500">*</span></label>
                        <input type="text" name="title" id="title" required
                            value="{{ old('title', $film->title) }}"
                            class="w-full px-4 py-3 border @error('title') border-red-500 @else border-gray-300 @enderror rounded-xl focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition duration-150">
                        @error('title')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Deskripsi --}}
                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
                        <textarea name="description" id="description" rows="5"
                            class="w-full px-4 py-3 border @error('description') border-red-500 @else border-gray-300 @enderror rounded-xl focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition duration-150">{{ old('description', $film->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Genre dan Rating --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="genre" class="block text-sm font-semibold text-gray-700 mb-2">Genre</label>
                            <input type="text" name="genre" id="genre"
                                value="{{ old('genre', $film->genre) }}"
                                class="w-full px-4 py-3 border @error('genre') border-red-500 @else border-gray-300 @enderror rounded-xl focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition duration-150"
                                placeholder="e.g., Action, Drama, Sci-Fi">
                            @error('genre')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="rating" class="block text-sm font-semibold text-gray-700 mb-2">Rating Usia</label>
                            <input type="text" name="rating" id="rating"
                                value="{{ old('rating', $film->rating) }}"
                                class="w-full px-4 py-3 border @error('rating') border-red-500 @else border-gray-300 @enderror rounded-xl focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition duration-150"
                                placeholder="PG-13, R, G">
                            @error('rating')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Durasi dan Tanggal Rilis --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="duration_minutes" class="block text-sm font-semibold text-gray-700 mb-2">Durasi (menit) <span class="text-red-500">*</span></label>
                            <input type="number" name="duration_minutes" id="duration_minutes" required
                                value="{{ old('duration_minutes', $film->duration_minutes) }}"
                                class="w-full px-4 py-3 border @error('duration_minutes') border-red-500 @else border-gray-300 @enderror rounded-xl focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition duration-150"
                                placeholder="120">
                            @error('duration_minutes')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="release_date" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Rilis</label>
                            <input type="date" name="release_date" id="release_date"
                                value="{{ old('release_date', $film->release_date->format('Y-m-d')) }}"
                                class="w-full px-4 py-3 border @error('release_date') border-red-500 @else border-gray-300 @enderror rounded-xl focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition duration-150">
                            @error('release_date')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan: Poster --}}
                <div class="lg:col-span-1 space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Poster Saat Ini</label>
                        {{-- Current Poster Preview --}}
                        @if($film->poster_path)
                        <div class="mb-4">
                            <img src="{{ $film->image }}" alt="Poster {{ $film->title }}"
                                class="w-full h-auto max-w-xs rounded-xl shadow-lg border border-gray-200 object-cover aspect-[2/3]">
                        </div>
                        @else
                        <div class="mb-4 w-full max-w-xs bg-gray-100 rounded-xl shadow-inner border border-dashed border-gray-300 flex items-center justify-center p-6 aspect-[2/3]">
                            <span class="text-gray-400 text-center text-sm">Tidak ada poster saat ini</span>
                        </div>
                        @endif

                        {{-- Upload Poster --}}
                        <label for="poster_file" class="block text-sm font-semibold text-gray-700 mt-4 mb-2">Ganti Poster (Opsional)</label>
                        <input type="file" name="poster_file" id="poster_file"
                            class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 file:transition file:duration-150">
                        @error('poster_file')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Tombol Submit --}}
            <div class="pt-6 border-t border-gray-200">
                <button type="submit"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white font-bold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transition duration-300 transform hover:scale-[1.005]">
                    <i data-lucide="save" class="w-5 h-5 inline mr-2 -mt-0.5"></i> Perbarui Film
                </button>
            </div>
        </form>
    </div>
</div>
@endsection