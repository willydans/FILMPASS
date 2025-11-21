{{-- 
  File: resources/views/partials/admin_sidebar.blade.php
--}}

<aside id="sidebar" class="sidebar bg-white border-r border-gray-200 p-6 flex flex-col justify-between fixed h-full z-30 lg:block">
    <div>
        <div class="mb-10 flex items-center">
            <span class="text-2xl font-bold text-gray-900">
                <span class="text-indigo-600">F</span>ilm<span class="text-indigo-600">P</span>ass Admin
            </span>
        </div>

        @php
            // Helper untuk aktif menu
            $isActive = function($route) {
                return request()->routeIs($route) 
                    ? 'text-indigo-700 bg-indigo-50 font-semibold' 
                    : 'text-gray-600 hover:bg-gray-100';
            };
        @endphp

        <nav class="space-y-2">

            {{-- Dashboard --}}
            <a href="{{ route('admin.dashboard') }}" 
               class="flex items-center p-3 rounded-lg transition duration-150 {{ $isActive('admin.dashboard') }}">
                <i data-lucide="layout-dashboard" class="w-5 h-5 mr-3"></i>
                Dashboard
            </a>

            {{-- Manajemen Film --}}
            <a href="{{ route('admin.films.index') }}" 
               class="flex items-center p-3 rounded-lg transition duration-150 {{ $isActive('admin.films.*') }}">
                <i data-lucide="film" class="w-5 h-5 mr-3"></i>
                Manajemen Film
            </a>

            {{-- Manajemen Studio --}}
            <a href="{{ route('admin.studios.index') }}" 
               class="flex items-center p-3 rounded-lg transition duration-150 {{ $isActive('admin.studios.*') }}">
                <i data-lucide="monitor" class="w-5 h-5 mr-3"></i>
                Manajemen Studio
            </a>

            {{-- Jadwal Tayang --}}
            <a href="{{ route('admin.schedules.index') }}" 
               class="flex items-center p-3 rounded-lg transition duration-150 {{ $isActive('admin.schedules.*') }}">
                <i data-lucide="calendar" class="w-5 h-5 mr-3"></i>
                Jadwal Tayang
            </a>

            {{-- Pemesanan --}}
            <a href="{{ route('admin.bookings.index') }}"
               class="flex items-center p-3 rounded-lg transition duration-150 {{ $isActive('admin.bookings.*') }}">
                <i data-lucide="ticket" class="w-5 h-5 mr-3"></i>
                Pemesanan
            </a>

            {{-- Laporan --}}
            <a href="{{ route('admin.reports.index') }}"
               class="flex items-center p-3 rounded-lg transition duration-150 {{ $isActive('admin.reports.*') }}">
                <i data-lucide="bar-chart-2" class="w-5 h-5 mr-3"></i>
                Laporan
            </a>
        </nav>
    </div>
</aside>
