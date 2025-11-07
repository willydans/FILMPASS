<?php

namespace App\HttpControllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\Request;

class FacilityController extends Controller
{

    public function index()
    {
        // Jika tidak sengaja ter-panggil, arahkan kembali ke studio
        return redirect()->route('admin.studios.index');
    }

    /**
     * Menyimpan fasilitas baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:facilities',
        ]);

        Facility::create($validated);

        // PERBAIKAN: Redirect kembali ke halaman studio
        return redirect()->route('admin.studios.index')
                         ->with('success', 'Fasilitas baru berhasil ditambahkan.');
    }

    /**
     * Menghapus fasilitas
     */
    public function destroy(Facility $facility)
    {
        try {
            $facilityName = $facility->name;
            $facility->delete();
            
            // PERBAIKAN: Redirect kembali ke halaman studio
            return redirect()->route('admin.studios.index')
                             ->with('success', "Fasilitas '$facilityName' berhasil dihapus.");

        } catch (\Illuminate\Database\QueryException $e) {
            
            // PERBAIKAN: Redirect kembali ke halaman studio
            return redirect()->route('admin.studios.index')
                             ->with('error', 'Gagal menghapus. Fasilitas mungkin masih digunakan oleh studio.');
        }
    }
}