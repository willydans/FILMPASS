<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Film;     // <-- 1. TAMBAHKAN IMPORT INI
use App\Models\Studio;   // <-- 2. TAMBAHKAN IMPORT INI
use Carbon\Carbon;

class ScheduleController extends Controller
{
    /**
     * Menampilkan halaman daftar jadwal tayang.
     */
    public function index(Request $request)
    {
        $filterDate = $request->input('filter_tanggal', now()->format('Y-m-d'));
        
        $schedulesQuery = Schedule::with(['film', 'studio'])
            ->whereDate('start_time', $filterDate)
            ->orderBy('start_time', 'asc');
        
        $schedules = $schedulesQuery->get();
        $schedules->loadSum('bookings', 'seat_count');
        $scheduleCount = $schedules->count();

        return view('admin.schedule', [
            'schedules' => $schedules,
            'scheduleCount' => $scheduleCount,
            'filterDate' => $filterDate
        ]);
    }

    /**
     * 3. TAMBAHKAN FUNGSI BARU INI
     * Menampilkan form untuk membuat jadwal baru.
     */
    public function create()
    {
        // Kita perlu mengambil daftar film dan studio
        // untuk ditampilkan di dropdown form
        $films = Film::orderBy('title')->get();
        $studios = Studio::orderBy('name')->get();

        // Kita akan membuat file view baru untuk form ini
        // Bernama 'schedule_create.blade.php'
        return view('admin.schedule_create', [
            'films' => $films,
            'studios' => $studios
        ]);
    }

    /**
     * (Placeholder) Menyimpan jadwal baru ke database.
     */
    public function store(Request $request)
    {
        // (Logika untuk menyimpan akan kita tambahkan nanti)
        abort(501, 'Fungsi Store Belum Dibuat');
    }

    // ... (fungsi edit, update, destroy lainnya)
}