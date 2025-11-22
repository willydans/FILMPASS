<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Film;
use App\Models\Studio;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Menampilkan halaman laporan dengan filter
     */
    public function index(Request $request)
    {
        // Ambil parameter filter dari request
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        $reportType = $request->input('report_type', 'overview');

        // Parse tanggal
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        // Data untuk semua jenis laporan
        $data = [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'reportType' => $reportType,
        ];

        switch ($reportType) {
            case 'sales':
                $data = array_merge($data, $this->getSalesReport($start, $end));
                break;
            
            case 'films':
                $data = array_merge($data, $this->getFilmsReport($start, $end));
                break;
            
            case 'studios':
                $data = array_merge($data, $this->getStudiosReport($start, $end));
                break;
            
            case 'users':
                $data = array_merge($data, $this->getUsersReport($start, $end));
                break;
            
            default: // overview
                $data = array_merge($data, $this->getOverviewReport($start, $end));
                break;
        }

        return view('admin.reports.index', $data);
    }

    /**
     * Laporan Overview (Dashboard Umum)
     */
    private function getOverviewReport($start, $end)
    {
        // Base Query untuk semua pemesanan dalam periode yang dipilih
        $allBookings = Booking::whereBetween('created_at', [$start, $end]);
        
        // Query untuk pemesanan yang DIKONFIRMASI (sumber pendapatan bersih)
        $confirmedBookingsQuery = Booking::whereBetween('created_at', [$start, $end])
                                         ->where('booking_status', 'confirmed');
        
        // Menghitung statistik utama dari yang Confirmed
        $totalTicketsConfirmed = $confirmedBookingsQuery->sum('seat_count');
        $totalRevenueConfirmed = $confirmedBookingsQuery->sum('total_price');
        $totalBookingsConfirmed = $confirmedBookingsQuery->count();

        return [
            'totalRevenue' => $totalRevenueConfirmed, 
            'totalBookings' => $allBookings->count(), // <-- Total SELURUH transaksi (termasuk pending)
            'totalTickets' => $totalTicketsConfirmed, 
            
            // Statistik Status (Gunakan base query lagi agar tidak bentrok)
            'confirmedBookings' => Booking::whereBetween('created_at', [$start, $end])->where('booking_status', 'confirmed')->count(),
            'cancelledBookings' => Booking::whereBetween('created_at', [$start, $end])->where('booking_status', 'cancelled')->count(),
            'pendingBookings' => Booking::whereBetween('created_at', [$start, $end])->where('booking_status', 'pending')->count(),

            // Rata-rata Transaksi dihitung dari Total Pendapatan / Total Booking Confirmed
            // Perbaikan: menggunakan $totalBookingsConfirmed untuk menghindari pembagian dengan nol
            'avgTicketPrice' => $totalBookingsConfirmed > 0 ? ($totalRevenueConfirmed / $totalBookingsConfirmed) : 0,
            
            // Revenue per hari (hanya dari Confirmed)
            'dailyRevenue' => Booking::whereBetween('created_at', [$start, $end])
                ->where('booking_status', 'confirmed')
                ->select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('SUM(total_price) as revenue'),
                    DB::raw('COUNT(*) as bookings')
                )
                ->groupBy('date')
                ->orderBy('date', 'asc')
                ->get(),
        ];
    }

    /**
     * Laporan Penjualan Detail
     */
    private function getSalesReport($start, $end)
    {
        return [
            'salesData' => Booking::with(['schedule.film', 'schedule.studio', 'user'])
                ->whereBetween('created_at', [$start, $end])
                ->orderBy('created_at', 'desc')
                ->paginate(20),
            
            // Ini tetap total Revenue dan Booking dari SEMUA status
            'totalRevenue' => Booking::whereBetween('created_at', [$start, $end])->sum('total_price'),
            'totalBookings' => Booking::whereBetween('created_at', [$start, $end])->count(),
        ];
    }

    /**
     * Laporan Film Terlaris
     */
    private function getFilmsReport($start, $end)
    {
        $topFilms = Booking::with(['schedule.film'])
            ->whereBetween('bookings.created_at', [$start, $end])
            ->join('schedules', 'bookings.schedule_id', '=', 'schedules.id')
            ->join('films', 'schedules.film_id', '=', 'films.id')
            ->select(
                'films.id',
                'films.title',
                'films.poster_path', 
                DB::raw('SUM(bookings.total_price) as total_revenue'),
                DB::raw('SUM(bookings.seat_count) as total_tickets'),
                DB::raw('COUNT(bookings.id) as total_bookings')
            )
            ->groupBy('films.id', 'films.title', 'films.poster_path')
            ->orderBy('total_revenue', 'desc')
            ->limit(10)
            ->get();

        return [
            'topFilms' => $topFilms,
            'totalFilms' => Film::count(),
        ];
    }

    /**
     * Laporan Pendapatan per Studio
     */
    private function getStudiosReport($start, $end)
    {
        $studioRevenue = Booking::with(['schedule.studio'])
            ->whereBetween('bookings.created_at', [$start, $end])
            ->join('schedules', 'bookings.schedule_id', '=', 'schedules.id')
            ->join('studios', 'schedules.studio_id', '=', 'studios.id')
            ->select(
                'studios.id',
                'studios.name',
                'studios.type',
                DB::raw('SUM(bookings.total_price) as total_revenue'),
                DB::raw('SUM(bookings.seat_count) as total_tickets'),
                DB::raw('COUNT(bookings.id) as total_bookings')
            )
            ->groupBy('studios.id', 'studios.name', 'studios.type')
            ->orderBy('total_revenue', 'desc')
            ->get();

        return [
            'studioRevenue' => $studioRevenue,
            'totalStudios' => Studio::count(),
        ];
    }

    /**
     * Laporan Statistik Pengguna
     */
    private function getUsersReport($start, $end)
    {
        $topUsers = Booking::with('user')
            ->whereBetween('created_at', [$start, $end])
            ->select(
                'user_id',
                DB::raw('COUNT(*) as total_bookings'),
                DB::raw('SUM(total_price) as total_spent'),
                DB::raw('SUM(seat_count) as total_tickets')
            )
            ->groupBy('user_id')
            ->orderBy('total_spent', 'desc')
            ->limit(20)
            ->get();

        return [
            'topUsers' => $topUsers,
            'totalUsers' => User::count(),
            'newUsers' => User::whereBetween('created_at', [$start, $end])->count(),
        ];
    }

    /**
     * Export Laporan ke PDF (Opsional - butuh library DomPDF)
     */
    public function exportPDF(Request $request)
    {
        // TODO: Implementasi export PDF dengan DomPDF
        return back()->with('info', 'Fitur export PDF sedang dalam pengembangan');
    }

    /**
     * Export Laporan ke Excel/CSV
     */
    public function exportExcel(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        $bookings = Booking::with(['schedule.film', 'schedule.studio', 'user'])
            ->whereBetween('created_at', [$start, $end])
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = "laporan_penjualan_{$startDate}_to_{$endDate}.csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($bookings) {
            $file = fopen('php://output', 'w');
            
            // Header CSV
            fputcsv($file, [
                'ID Booking',
                'Tanggal',
                'Film',
                'Studio',
                'User',
                'Jumlah Tiket',
                'Total Harga',
                'Status'
            ]);

            // Data CSV
            foreach ($bookings as $booking) {
                fputcsv($file, [
                    $booking->id,
                    $booking->created_at->format('Y-m-d H:i:s'),
                    $booking->schedule->film->title ?? '-',
                    $booking->schedule->studio->name ?? '-',
                    $booking->user->name ?? '-',
                    $booking->seat_count,
                    $booking->total_price,
                    $booking->booking_status,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}