@extends('layouts.admin')

@section('title', 'Tambah Film Baru')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-lg">

        {{-- HEADER --}}
        <div class="mb-8">
            <a href="{{ route('admin.films.index') }}" class="flex items-center text-sm text-gray-600 hover:text-indigo-600 transition">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                Kembali ke Manajemen Film
            </a>
            <h1 class="text-3xl font-bold text-gray-800 mt-2">Tambah Film Baru</h1>
            <p class="text-gray-500">Isi detail film di bawah ini.</p>
        </div>

        {{-- FORM --}}
        {{-- Arahkan ke route 'admin.films.store' dan gunakan method POST --}}
        {{-- 'enctype="multipart/form-data"' WAJIB untuk upload file --}}
        <form action="{{ route('admin.films.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf  {{-- Token keamanan Laravel --}}

            {{-- Judul Film --}}
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Judul Film</label>
                <input type="text" name="title" id="title" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            {{-- Deskripsi --}}
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea name="description" id="description" rows="4"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"></textarea>
            </div>

            {{-- Genre --}}
            <div>
                <label for="genre" class="block text-sm font-medium text-gray-700 mb-1">Genre</label>
                <input type="text" name="genre" id="genre"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                       placeholder="e.g., Action, Drama, Sci-Fi">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Durasi (dalam menit) --}}
                <div>
                    <label for="duration_minutes" class="block text-sm font-medium text-gray-700 mb-1">Durasi (menit)</label>
                    <input type="number" name="duration_minutes" id="duration_minutes" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="e.g., 120">
                </div>

                {{-- Tanggal Rilis --}}
                <div>
                    <label for="release_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Rilis</label>
                    <input type="date" name="release_date" id="release_date"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Rating --}}
                <div>
                    <label for="rating" class="block text-sm font-medium text-gray-700 mb-1">Rating Usia</label>
                    <input type="text" name="rating" id="rating"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="e.g., PG-13, R, G">
                </div>

                {{-- Upload Poster --}}
                <div>
                    <label for="poster_file" class="block text-sm font-medium text-gray-700 mb-1">Poster Film</label>
                    {{-- Ganti nama input menjadi 'poster_file' agar sesuai dengan controller --}}
                    <input type="file" name="poster_file" id="poster_file" required
                           class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                </div>
            </div>

            {{-- Tombol Submit --}}
            <div class="pt-4 border-t border-gray-200">
                <button type="submit"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300">
                    Simpan Film
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
