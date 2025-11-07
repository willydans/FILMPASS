<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Studio; // <-- 1. IMPORT MODEL
use Illuminate\Http\Request;

class StudioController extends Controller
{
    /**
     * Menampilkan halaman daftar studio.
     */
    public function index()
    {
        // 2. Ambil semua data studio dari database
        $studios = Studio::orderBy('name', 'asc')->get();
        
        // 3. Kirim data '$studios' ke file view 'admin.studio'
        return view('admin.studio', [
            'studios' => $studios
        ]);
    }

    /**
     * Menampilkan form untuk membuat studio baru.
     */
    public function create()
    {
        // Nanti Anda akan membuat file: /resources/views/admin/studio_create.blade.php
        // return view('admin.studio_create');
        
        // Untuk saat ini, kita bisa kembalikan teks placeholder:
        return "Halaman Form Tambah Studio Baru (Belum Dibuat)";
    }

    /**
     * Menyimpan studio baru ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi data yang masuk dari form
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:studios',
            'type' => 'required|string|max:100',
            'capacity' => 'required|integer|min:1',
            // Tambahkan validasi lain jika ada (misal: fasilitas)
        ]);

        // 2. Buat dan simpan studio baru
        // (Ini berfungsi karena Anda sudah mengatur $fillable di Model Studio)
        Studio::create($validated);

        // 3. Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('admin.studios.index') // Perhatikan: 'studios.index' (plural)
                         ->with('success', 'Studio baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan data studio spesifik (biasanya tidak dipakai di admin panel).
     */
    public function show(Studio $studio)
    {
        // Kita gunakan Route Model Binding (Studio $studio)
        // Jika diakses, redirect saja ke halaman edit
        return redirect()->route('admin.studios.edit', $studio->id);
    }

    /**
     * Menampilkan form untuk mengedit studio.
     */
    public function edit(Studio $studio)
    {
        // Nanti Anda akan membuat file: /resources/views/admin/studio_edit.blade.php
        // return view('admin.studio_edit', ['studio' => $studio]);
        
        // Untuk saat ini, kita bisa kembalikan teks placeholder:
        return "Halaman Form Edit untuk: " . $studio->name . " (Belum Dibuat)";
    }

    /**
     * Memperbarui data studio di database.
     */
    public function update(Request $request, Studio $studio)
    {
        // 1. Validasi data
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:studios,name,' . $studio->id,
            'type' => 'required|string|max:100',
            'capacity' => 'required|integer|min:1',
        ]);

        // 2. Update studio yang ada
        $studio->update($validated);

        // 3. Redirect kembali
        return redirect()->route('admin.studios.index')
                         ->with('success', 'Studio ' . $studio->name . ' berhasil diperbarui.');
    }

    /**
     * Menghapus studio dari database.
     */
    public function destroy(Studio $studio)
    {
        try {
            // 1. Hapus studio
            $studioName = $studio->name;
            $studio->delete();
            
            // 2. Redirect dengan pesan sukses
            return redirect()->route('admin.studios.index')
                             ->with('success', 'Studio ' . $studioName . ' berhasil dihapus.');
                             
        } catch (\Illuminate\Database\QueryException $e) {
            // 3. Tangani error jika studio tidak bisa dihapus (misal: masih ada jadwal)
            return redirect()->route('admin.studios.index')
                             ->with('error', 'Gagal menghapus ' . $studio->name . '. Pastikan tidak ada jadwal yang terikat.');
        }
    }
}