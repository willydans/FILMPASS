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
    /**
     * Menampilkan halaman dashboard admin dengan data.
     */
    public function index()
    {
        // 1. Ambil Data untuk Kartu Statistik
        $totalFilm = Film::count();
        $totalPemesanan = Booking::where('booking_status', 'confirmed')->count();
        $totalPendapatan = Booking::where('booking_status', 'confirmed')->sum('total_price');
        
        // Menghitung pengguna (selain admin dan kasir)
        $totalPengguna = User::where('role', 'user')->count();

        // 2. Ambil Data untuk "Penjualan Mingguan"
        // Ini adalah query yang lebih kompleks untuk mengambil data 7 hari terakhir
        $salesData = Booking::select(
                DB::raw('DATE(created_at) as tanggal'),
                DB::raw('SUM(total_price) as total')
            )
            ->where('booking_status', 'confirmed')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get();
            
        // Format data untuk chart (dengan nama hari dalam Bahasa Indonesia)
        $penjualanMingguan = [];
        $maxSales = $salesData->max('total') ?: 1; // Hindari pembagian dengan nol

        // Inisialisasi 7 hari terakhir
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dayName = $date->locale('id')->isoFormat('ddd'); // 'Sen', 'Sel', 'Rab', ...
            
            // Cari data penjualan untuk hari ini
            $sale = $salesData->firstWhere('tanggal', $date->format('Y-m-d'));
            
            $total = $sale ? $sale->total : 0;
            
            $penjualanMingguan[] = [
                'hari' => ucfirst($dayName),
                'total' => $total,
                'persentase' => ($total / $maxSales) * 100
            ];
        }

        // 3. Kirim semua data ke view
        return view('admin.dashboard', [
            'totalFilm' => $totalFilm,
            'totalPemesanan' => $totalPemesanan,
            'totalPendapatan' => $totalPendapatan,
            'totalPengguna' => $totalPengguna,
            'penjualanMingguan' => $penjualanMingguan
        ]);
    }
}