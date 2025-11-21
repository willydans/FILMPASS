<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan - FilmPass Admin</title>
    
    @include('partials.head')
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        .sidebar { width: 256px; min-height: 100vh; }
        .content { margin-left: 0; }
        @media (min-width: 1024px) { .content { margin-left: 256px; } }
    </style>
</head>
<body class="bg-gray-50">

    {{-- SIDEBAR --}}
    <aside id="sidebar" class="sidebar bg-white border-r border-gray-200 p-6 flex flex-col justify-between fixed h-full z-30 lg:block">
        <div>
            <div class="mb-10 flex items-center">
                <span class="text-2xl font-bold text-gray-900">
                    <span class="text-indigo-600">F</span>ilm<span class="text-indigo-600">P</span>ass Admin
                </span>
            </div>

            @php
                $isActive = function($route) {
                    return request()->routeIs($route) ? 'text-indigo-700 bg-indigo-50 font-semibold' : 'text-gray-600 hover:bg-gray-100';
                };
            @endphp

            <nav class="space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center p-3 rounded-lg transition {{ $isActive('admin.dashboard') }}">
                    <i data-lucide="layout-dashboard" class="w-5 h-5 mr-3"></i>
                    Dashboard
                </a>
                <a href="{{ route('admin.films.index') }}" class="flex items-center p-3 rounded-lg transition {{ $isActive('admin.films.*') }}">
                    <i data-lucide="film" class="w-5 h-5 mr-3"></i>
                    Manajemen Film
                </a>
                <a href="{{ route('admin.studios.index') }}" class="flex items-center p-3 rounded-lg transition {{ $isActive('admin.studios.*') }}">
                    <i data-lucide="monitor" class="w-5 h-5 mr-3"></i>
                    Manajemen Studio
                </a>
                <a href="{{ route('admin.schedules.index') }}" class="flex items-center p-3 rounded-lg transition {{ $isActive('admin.schedules.*') }}">
                    <i data-lucide="calendar" class="w-5 h-5 mr-3"></i>
                    Jadwal Tayang
                </a>
                <a href="{{ route('admin.bookings.index') }}" class="flex items-center p-3 rounded-lg transition {{ $isActive('admin.bookings.*') }}">
                    <i data-lucide="ticket" class="w-5 h-5 mr-3"></i>
                    Pemesanan
                </a>
                <a href="{{ route('admin.reports.index') }}" class="flex items-center p-3 rounded-lg transition {{ $isActive('admin.reports.*') }}">
                    <i data-lucide="bar-chart-2" class="w-5 h-5 mr-3"></i>
                    Laporan
                </a>
            </nav>
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <div id="main-content" class="content p-0 w-full flex flex-col flex-grow">
        
        {{-- HEADER --}}
        <header class="bg-white p-4 border-b border-gray-200 flex justify-between items-center fixed top-0 w-full lg:w-[calc(100%-256px)] z-20 shadow-sm">
            <button id="menu-toggle" class="text-gray-500 hover:text-gray-700 lg:hidden p-1 mr-2">
                <i data-lucide="menu" class="w-6 h-6"></i>
            </button>
            <div class="flex-grow"></div>
            <div class="flex items-center space-x-4">
                <button class="text-gray-500 hover:text-gray-700">
                    <i data-lucide="bell" class="w-5 h-5"></i>
                </button>
                
                <div x-data="{ open: false }" class="relative">
                    <div @click="open = !open" class="flex items-center space-x-2 text-sm font-medium text-gray-700 cursor-pointer p-2 rounded-full hover:bg-gray-100 transition">
                        <div class="bg-indigo-600 text-white w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm">
                            {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                        </div>
                        <span>{{ Auth::user()->name ?? 'Admin' }}</span>
                        <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400"></i>
                    </div>

                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50" style="display: none;">
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left flex items-center px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                <i data-lucide="log-out" class="w-4 h-4 mr-2"></i>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        {{-- MAIN CONTENT --}}
        <main class="pt-20 px-6 pb-12 flex-grow">
            
            {{-- HEADER LAPORAN --}}
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Laporan & Analitik</h1>
                <p class="text-gray-600">Lihat statistik dan laporan lengkap sistem FilmPass</p>
            </div>

            {{-- FILTER FORM --}}
            <form method="GET" action="{{ route('admin.reports.index') }}" class="bg-white p-6 rounded-xl shadow-md mb-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    
                    {{-- Pilih Jenis Laporan --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Laporan</label>
                        <select name="report_type" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
                            <option value="overview" {{ $reportType == 'overview' ? 'selected' : '' }}>📊 Overview</option>
                            <option value="sales" {{ $reportType == 'sales' ? 'selected' : '' }}>💰 Penjualan</option>
                            <option value="films" {{ $reportType == 'films' ? 'selected' : '' }}>🎬 Film Terlaris</option>
                            <option value="studios" {{ $reportType == 'studios' ? 'selected' : '' }}>🏢 Studio</option>
                            <option value="users" {{ $reportType == 'users' ? 'selected' : '' }}>👥 Pengguna</option>
                        </select>
                    </div>

                    {{-- Tanggal Mulai --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Mulai</label>
                        <input type="date" name="start_date" value="{{ $startDate }}" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
                    </div>

                    {{-- Tanggal Akhir --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Akhir</label>
                        <input type="date" name="end_date" value="{{ $endDate }}" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
                    </div>

                    {{-- Tombol Filter --}}
                    <div class="flex items-end space-x-2">
                        <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-semibold transition">
                            <i data-lucide="search" class="w-4 h-4 inline mr-1"></i>
                            Filter
                        </button>
                    </div>
                </div>

                {{-- Tombol Export --}}
                <div class="mt-4 flex space-x-3">
                    <a href="{{ route('admin.reports.export.excel', request()->query()) }}" 
                       class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold transition inline-flex items-center">
                        <i data-lucide="file-spreadsheet" class="w-4 h-4 mr-2"></i>
                        Export Excel
                    </a>
                    <a href="{{ route('admin.reports.export.pdf', request()->query()) }}" 
                       class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-semibold transition inline-flex items-center">
                        <i data-lucide="file-text" class="w-4 h-4 mr-2"></i>
                        Export PDF
                    </a>
                </div>
            </form>

            {{-- KONTEN LAPORAN --}}
            @if($reportType == 'overview')
                @include('admin.reports.partials.overview')
            @elseif($reportType == 'sales')
                @include('admin.reports.partials.sales')
            @elseif($reportType == 'films')
                @include('admin.reports.partials.films')
            @elseif($reportType == 'studios')
                @include('admin.reports.partials.studios')
            @elseif($reportType == 'users')
                @include('admin.reports.partials.users')
            @endif

        </main>
    </div>

    <script>
        // Sidebar toggle
        const sidebar = document.getElementById('sidebar');
        const toggleButton = document.getElementById('menu-toggle');
        
        if (toggleButton) {
            if (window.innerWidth < 1024) sidebar.classList.add('-translate-x-full');
            
            toggleButton.addEventListener('click', () => {
                sidebar.classList.toggle('-translate-x-full');
            });
        }

        // Initialize Lucide icons
        lucide.createIcons();
    </script>
</body>
</html>