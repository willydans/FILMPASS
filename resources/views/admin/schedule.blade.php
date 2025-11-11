<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Tayang - FilmPass Admin</title>

    <!-- Load Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        'fp-blue': '#1c7ed6',
                        'fp-dark': '#1e293b',
                    }
                }
            }
        }
    </script>

    <style>
        .admin-header {
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        .sidebar {
            width: 280px;
        }
    </style>
</head>
<body class="font-sans bg-gray-50">

    <!-- HEADER -->
    @include('partials.header')

    <div class="flex min-h-screen">
        <!-- SIDEBAR -->
        @include('partials.admin_sidebar')

        <!-- MAIN CONTENT -->
        <main class="flex-1 p-8 overflow-y-auto">

            <!-- Header Section -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Jadwal Tayang</h1>
                    <p class="text-gray-600">Kelola jadwal tayang film di semua studio</p>
                </div>
                <div>
                    <a href="{{ route('admin.schedules.create') }}" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2.5 rounded-lg inline-flex items-center transition duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Tambah Jadwal
                    </a>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="bg-white p-4 rounded-xl shadow-md mb-6 border border-gray-200">
                <form action="{{ route('admin.schedules.index') }}" method="GET"
                      class="flex flex-col md:flex-row md:items-center space-y-4 md:space-y-0 md:space-x-8">
                    
                    <!-- Filter Tanggal -->
                    <div class="flex items-center space-x-3">
                        <label for="filter_tanggal" class="text-sm font-medium text-gray-700 whitespace-nowrap">Filter Tanggal:</label>
                        <input type="date" name="filter_tanggal" id="filter_tanggal" value="{{ $filterDate }}"
                            class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 max-w-[150px]">
                    </div>

                    <!-- Filter Film -->
                    <div class="flex items-center space-x-3">
                        <label for="film_id" class="text-sm font-medium text-gray-700 whitespace-nowrap hidden sm:block">Film:</label>
                        <select name="film_id" id="film_id"
                            class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 w-full md:w-auto">
                            <option value="">Semua Film</option>
                            @foreach ($films as $film)
                                <option value="{{ $film->id }}" {{ $filterFilmId == $film->id ? 'selected' : '' }}>
                                    {{ $film->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tombol Filter -->
                    <div class="flex items-center space-x-4">
                        <button type="submit"
                            class="bg-blue-500 text-white px-4 py-2 text-sm rounded-lg hover:bg-blue-600 w-full md:w-auto transition duration-200">
                            Filter
                        </button>
                        <span class="text-gray-500 text-sm whitespace-nowrap">
                            {{ $scheduleCount }} jadwal ditemukan
                        </span>
                    </div>
                </form>
            </div>

            <!-- TABEL JADWAL -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">

                <!-- Header Tabel -->
                <div class="grid grid-cols-12 gap-4 px-6 py-4 border-b bg-gray-50 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    <div class="col-span-3">Film</div>
                    <div class="col-span-2">Studio</div>
                    <div class="col-span-2">Waktu</div>
                    <div class="col-span-1 text-center">Harga</div>
                    <div class="col-span-2">Okupansi</div>
                    <div class="col-span-1">Status</div>
                    <div class="col-span-1 text-center">Aksi</div>
                </div>

                <!-- ROWS -->
                @foreach ($schedules as $schedule)
                <div class="grid grid-cols-12 gap-4 px-6 py-4 items-center border-b border-gray-200 hover:bg-gray-50 transition duration-100">
                    <div class="col-span-3">
                        <span class="text-sm font-semibold text-gray-800">{{ $schedule->film->title }}</span>
                    </div>
                    <div class="col-span-2">
                        <a href="#studio-{{ $schedule->studio_id }}" class="text-sm text-blue-600 hover:text-blue-800 hover:underline">
                            {{ $schedule->studio->name }}
                        </a>
                    </div>
                    <div class="col-span-2">
                        <span class="text-sm text-gray-700">{{ $schedule->start_time }} - {{ $schedule->end_time }}</span>
                    </div>
                    <div class="col-span-1 text-center">
                        <span class="text-sm font-bold text-blue-600">Rp {{ number_format($schedule->price, 0, ',', '.') }}</span>
                    </div>
                    <div class="col-span-2">
                        <div class="text-xs text-gray-600">Data okupansi belum tersedia</div>
                    </div>
                    <div class="col-span-1">
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-blue-100 text-blue-700">Aktif</span>
                    </div>
                    <div class="col-span-1 flex justify-center space-x-2">
                        <a href="{{ route('admin.schedules.edit', $schedule->id) }}" class="text-blue-500 hover:text-blue-700" title="Edit">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414
                                      a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </a>
                        <form action="{{ route('admin.schedules.destroy', $schedule->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus jadwal ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700" title="Hapus">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862
                                          a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6
                                          m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1
                                          1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach

                @if($schedules->isEmpty())
                <div class="text-center text-gray-500 p-6">
                    Tidak ada jadwal tayang ditemukan untuk tanggal ini.
                </div>
                @endif
            </div>

        </main>
    </div>

    <!-- FOOTER -->
    @include('partials.footer')

</body>
</html>
