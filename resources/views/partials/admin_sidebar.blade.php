{{-- Ini adalah Sidebar dari screenshot Dashboard --}}
<aside class="w-64 bg-white shadow-md flex-shrink-0">
    <div class="p-4 pt-6">
        <nav>
            <ul>
                {{-- Helper untuk cek rute aktif --}}
                @php
                    $isActive = function($route) {
                        return request()->routeIs($route) ? 'bg-blue-100 text-blue-600' : 'text-gray-600 hover:bg-gray-100';
                    };
                @endphp

                <li class="mb-2">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center px-4 py-2.5 rounded-lg font-medium {{ $isActive('admin.dashboard') }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        Dashboard
                    </a>
                </li>
                <li class="mb-2">
                    <a href="{{ route('admin.films.index') }}" 
                       class="flex items-center px-4 py-2.5 rounded-lg font-medium {{ $isActive('admin.films.*') }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"></path></svg>
                        Manajemen Film
                    </a>
                </li>
                <li class="mb-2">
                    <a href="#" {{-- Nanti ganti ke route('admin.studio.index') --}}
                       class="flex items-center px-4 py-2.5 rounded-lg font-medium {{ $isActive('admin.studio.*') }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        Manajemen Studio
                    </a>
                </li>
                <li class="mb-2">
                    {{-- Kode ini 100% Benar --}}
                    <a href="{{ route('admin.schedules.index') }}" 
                       class="flex items-center px-4 py-2.5 rounded-lg font-medium {{ $isActive('admin.schedules.*') }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Jadwal Tayang
                    </a>
                </li>
                <li class="mb-2">
                    <a href="#" {{-- Nanti ganti ke route('admin.bookings.index') --}}
                       class="flex items-center px-4 py-2.5 rounded-lg font-medium {{ $isActive('admin.bookings.*') }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                        Pemesanan
                    </a>
                </li>
                <li class="mb-2">
                    <a href="#" {{-- Nanti ganti ke route('admin.reports.index') --}}
                       class="flex items-center px-4 py-2.5 rounded-lg font-medium {{ $isActive('admin.reports.*') }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Laporan
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>