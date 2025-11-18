<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Film;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. DATA STATISTIK UTAMA
        $totalFilm = Film::count();
        $totalPemesanan = Booking::where('booking_status', 'confirmed')->count();
        $totalPendapatan = Booking::where('booking_status', 'confirmed')->sum('total_price');
        $totalPengguna = User::where('role', 'user')->count();

        // 2. DATA PENJUALAN MINGGUAN (Grafik)
        $salesData = Booking::select(
                DB::raw('DATE(created_at) as tanggal'),
                DB::raw('SUM(total_price) as total')
            )
            ->where('booking_status', 'confirmed')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get();
            
        $penjualanMingguan = [];
        $maxSales = $salesData->max('total') ?: 1;

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dayName = $date->locale('id')->isoFormat('ddd'); 
            
            $sale = $salesData->firstWhere('tanggal', $date->format('Y-m-d'));
            $total = $sale ? $sale->total : 0;
            
            $penjualanMingguan[] = [
                'hari' => ucfirst($dayName),
                'total' => $total,
                'persentase' => ($total / $maxSales) * 100
            ];
        }

        // 3. DATA FILM POPULER (BARU)
        // Mengambil 6 film secara acak untuk ditampilkan di dashboard
        $popularFilms = Film::inRandomOrder()->take(6)->get();

        return view('admin.dashboard', [
            'totalFilm' => $totalFilm,
            'totalPemesanan' => $totalPemesanan,
            'totalPendapatan' => $totalPendapatan,
            'totalPengguna' => $totalPengguna,
            'penjualanMingguan' => $penjualanMingguan,
            'popularFilms' => $popularFilms // <-- Kirim data ini
        ]);
    }
}