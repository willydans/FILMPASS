<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Film;
use App\Models\Studio;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    /**
     * Menampilkan halaman daftar jadwal tayang.
     */
    public function index(Request $request)
    {
        $filterDate = $request->input('filter_tanggal', now()->format('Y-m-d'));
        $filterFilmId = $request->input('film_id'); // Ambil ID film dari request
        
        // Query jadwal dengan eager load film & studio
        $schedulesQuery = Schedule::with(['film', 'studio'])
            ->whereDate('start_time', $filterDate)
            ->orderBy('start_time', 'asc');

        // Terapkan filter film jika ada
        if ($filterFilmId) {
            $schedulesQuery->where('film_id', $filterFilmId);
        }
        
        $schedules = $schedulesQuery->get();

        // Hitung total bookings per jadwal
        $schedules->loadSum('bookings', 'seat_count');

        $scheduleCount = $schedules->count();

        // Ambil semua film untuk dropdown filter
        $films = Film::orderBy('title')->get();

        return view('admin.schedule', [
            'schedules' => $schedules,
            'scheduleCount' => $scheduleCount,
            'filterDate' => $filterDate,
            'films' => $films,
            'filterFilmId' => $filterFilmId
        ]);
    }

    /**
     * Menampilkan form untuk membuat jadwal baru.
     */
    public function create()
    {
        // Ambil semua film dan studio
        $films = Film::orderBy('title')->get();
        $studios = Studio::orderBy('name')->get();

        return view('admin.schedule_create', [
            'films' => $films,
            'studios' => $studios
        ]);
    }

    /**
     * Menyimpan jadwal baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'film_id' => 'required|exists:films,id',
            'studio_id' => 'required|exists:studios,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'price' => 'required|numeric|min:0',
        ]);

        // Simpan jadwal baru
        Schedule::create([
            'film_id' => $request->film_id,
            'studio_id' => $request->studio_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'price' => $request->price,
        ]);

        return redirect()->route('admin.schedules.index')
                         ->with('success', 'Jadwal tayang berhasil ditambahkan.');
    }

    // Fungsi edit, update, destroy bisa ditambahkan sesuai kebutuhan
}
