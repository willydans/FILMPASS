<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Studio;
use App\Models\Facility; // <-- Pastikan ini di-import
use Illuminate\Http\Request;

class StudioController extends Controller
{
    /**
     * Menampilkan halaman daftar studio.
     */
    public function index()
    {
        // PERBAIKAN 1:
        // Kita tambahkan 'withSum' untuk menghitung okupansi
        // Ini akan mengambil relasi 'bookings' dari Model Studio
        // dan menjumlahkan (SUM) kolom 'seat_count'
        $studios = Studio::with('facilities')
                         ->withSum('bookings', 'seat_count') // <-- INI YANG BARU
                         ->orderBy('name', 'asc')
                         ->get();
        
        // Ambil juga semua fasilitas untuk modal
        $facilities = Facility::orderBy('name', 'asc')->get();

        // Kirim KEDUA data ke file view
        return view('admin.studio', [
            'studios' => $studios,
            'facilities' => $facilities
        ]);
    }

    /**
     * FUNGSI BARU DITAMBAHKAN:
     * Untuk mengubah status studio (Aktif / Maintenance)
     */
    public function toggleStatus(Studio $studio)
    {
        // 1. Ubah statusnya
        $newStatus = ($studio->status == 'Aktif') ? 'Maintenance' : 'Aktif';
        $studio->status = $newStatus;
        $studio->save();
        
        // 2. Redirect kembali ke halaman studio
        return redirect()->route('admin.studios.index')
                         ->with('success', 'Status ' . $studio->name . ' diubah menjadi ' . $newStatus);
    }

    /**
     * Menampilkan form untuk membuat studio baru.
     */
    public function create()
    {
        $facilities = Facility::orderBy('name')->get();
        
        // Pastikan Anda sudah membuat file ini: /resources/views/admin/studio_create.blade.php
        return view('admin.studio_create', [
            'facilities' => $facilities
        ]);
    }

    /**
     * Menyimpan studio baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:studios',
            'type' => 'required|string|max:100',
            'capacity' => 'required|integer|min:1',
            'base_price' => 'required|integer|min:0',
            'status' => 'required|string|in:Aktif,Maintenance',
            'description' => 'nullable|string',
            'facilities' => 'nullable|array',
            'facilities.*' => 'exists:facilities,id'
        ]);

        $studio = Studio::create($validated);

        if ($request->has('facilities')) {
            $studio->facilities()->sync($request->facilities);
        }

        return redirect()->route('admin.studios.index')
                         ->with('success', 'Studio baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan data studio spesifik (redirect ke edit).
     */
    public function show(Studio $studio)
    {
        return redirect()->route('admin.studios.edit', $studio->id);
    }

    /**
     * Menampilkan form untuk mengedit studio.
     */
    public function edit(Studio $studio)
    {
        $facilities = Facility::orderBy('name')->get();
        $studio->load('facilities');
        
        // Pastikan Anda sudah membuat file ini: /resources/views/admin/studio_edit.blade.php
        return view('admin.studio_edit', [
            'studio' => $studio,
            'facilities' => $facilities
        ]);
    }

    /**
     * Memperbarui data studio di database.
     */
    public function update(Request $request, Studio $studio)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:studios,name,' . $studio->id,
            'type' => 'required|string|max:100',
            'capacity' => 'required|integer|min:1',
            'base_price' => 'required|integer|min:0',
            'status' => 'required|string|in:Aktif,Maintenance',
            'description' => 'nullable|string',
            'facilities' => 'nullable|array',
            'facilities.*' => 'exists:facilities,id'
        ]);

        $studio->update($validated);
        $studio->facilities()->sync($request->input('facilities', []));

        return redirect()->route('admin.studios.index')
                         ->with('success', 'Studio ' . $studio->name . ' berhasil diperbarui.');
    }

    /**
     * Menghapus studio dari database.
     */
    public function destroy(Studio $studio)
    {
        try {
            $studioName = $studio->name;
            $studio->delete();
            
            return redirect()->route('admin.studios.index')
                             ->with('success', 'Studio ' . $studioName . ' berhasil dihapus.');
                             
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('admin.studios.index')
                             ->with('error', 'Gagal menghapus ' . $studio->name . '. Pastikan tidak ada jadwal yang terikat.');
        }
    }
}