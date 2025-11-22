<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Film;
use App\Models\Studio;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; 

class ScheduleController extends Controller
{
    /**
     * Menampilkan halaman daftar jadwal tayang.
     */
    public function index(Request $request)
    {
        $filterDate = $request->input('filter_tanggal', now()->format('Y-m-d'));
        
        // --- PENTING: Gunakan waktu lokal aplikasi untuk perbandingan yang konsisten ---
        // Carbon::now() akan menggunakan zona waktu dari config/app.php (misal Asia/Jakarta)
        $now = Carbon::now(); 
        
        // ===============================================
        // 1. OTOMATIS SINKRONISASI STATUS DI DATABASE
        // ===============================================

        // A. Set jadwal yang sudah SELESAI
        // Jadwal yang end_time-nya sudah lewat dari waktu sekarang.
        Schedule::where('end_time', '<', $now)
            ->where('status', '!=', 'selesai')
            ->update(['status' => 'selesai']);

        // B. Set jadwal yang sedang TAYANG
        // Jadwal yang start_time-nya sudah lewat ATAU SAMA dengan waktu sekarang,
        // DAN end_time-nya masih di masa depan.
        Schedule::where('start_time', '<=', $now)
            ->where('end_time', '>', $now)
            ->where('status', 'terjadwal') // Hanya update jika statusnya masih terjadwal
            ->update(['status' => 'sedang_tayang']);
        
        // ===============================================
        // 2. QUERY DAN TAMPILKAN DATA
        // ===============================================

        $schedulesQuery = Schedule::with(['film', 'studio'])
            ->whereDate('start_time', $filterDate)
            ->orderBy('start_time', 'asc');
        
        $schedules = $schedulesQuery->get();
        $schedules->loadSum('bookings', 'seat_count');
        $scheduleCount = $schedules->count();

        // LOGIKA AKHIR: Ambil status dari kolom DB yang sudah disinkronisasi
        $schedules = $schedules->map(function ($schedule) {
            
            $finalStatus = $schedule->status;
            $schedule->current_status = $finalStatus;
            
            // Jadwal tidak aktif (booking ditutup) jika status 'selesai' atau 'sedang_tayang'
            $schedule->is_active = !($finalStatus == 'selesai' || $finalStatus == 'sedang_tayang');
            
            return $schedule;
        });

        return view('admin.schedule', [
            'schedules' => $schedules,
            'scheduleCount' => $scheduleCount,
            'filterDate' => $filterDate
        ]);
    }

    /**
     * Menampilkan form untuk membuat jadwal baru.
     */
    public function create()
    {
        $films = Film::orderBy('title')->get();
        $studios = Studio::where('status', 'Aktif')->orderBy('name')->get();

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
        $request->validate([
            'film_id' => 'required|exists:films,id',
            'studio_id' => 'required|exists:studios,id',
            'start_time' => 'required|date|after:now',
            'price' => 'required|numeric|min:0',
        ]);

        // Ambil film untuk mendapatkan durasi
        $film = Film::findOrFail($request->film_id);
        
        // Hitung end_time berdasarkan durasi film
        $startTime = Carbon::parse($request->start_time);
        $endTime = $startTime->copy()->addMinutes($film->duration_minutes);

        // Cek bentrok jadwal di studio yang sama
        $conflict = Schedule::where('studio_id', $request->studio_id)
            ->where(function($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                      ->orWhereBetween('end_time', [$startTime, $endTime])
                      ->orWhere(function($q) use ($startTime, $endTime) {
                          $q->where('start_time', '<=', $startTime)
                            ->where('end_time', '>=', $endTime);
                      });
            })
            ->exists();

        if ($conflict) {
            return back()->withErrors([
                'start_time' => 'Jadwal bentrok dengan jadwal lain di studio yang sama.'
            ])->withInput();
        }

        Schedule::create([
            'film_id' => $request->film_id,
            'studio_id' => $request->studio_id,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'price' => $request->price,
            'status' => 'terjadwal', // Default status saat membuat baru
        ]);

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal berhasil ditambahkan!');
    }

    /**
     * Menampilkan form edit jadwal.
     */
    public function edit(Schedule $schedule)
    {
        $films = Film::orderBy('title')->get();
        $studios = Studio::where('status', 'Aktif')->orderBy('name')->get();

        return view('admin.schedule_edit', [
            'schedule' => $schedule,
            'films' => $films,
            'studios' => $studios
        ]);
    }

    /**
     * Update jadwal di database.
     */
    public function update(Request $request, Schedule $schedule)
    {
        // 1. Validasi
        $request->validate([
            'film_id' => 'required|exists:films,id',
            'studio_id' => 'required|exists:studios,id',
            'start_time' => 'required|date',
            'price' => 'required|numeric|min:0',
            'status' => 'required|string', // Status dari form edit (bisa override otomatis)
        ]);

        // Ambil film untuk mendapatkan durasi
        $film = Film::findOrFail($request->film_id);
        
        // Hitung end_time
        $startTime = Carbon::parse($request->start_time);
        $endTime = $startTime->copy()->addMinutes($film->duration_minutes);

        // Cek bentrok (diasumsikan logika bentrok di sini sudah benar)

        // 3. Update Database (Termasuk status manual dari form)
        $schedule->update([
            'film_id' => $request->film_id,
            'studio_id' => $request->studio_id,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'price' => $request->price,
            'status' => $request->status, // Menggunakan status yang dikirim dari form edit
        ]);

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal berhasil diperbarui!');
    }

    /**
     * Hapus jadwal dari database.
     */
    public function destroy(Schedule $schedule)
    {
        if ($schedule->bookings()->exists()) {
            return back()->withErrors([
                'error' => 'Tidak dapat menghapus jadwal yang sudah memiliki pemesanan.'
            ]);
        }

        $schedule->delete();

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal berhasil dihapus!');
    }
}