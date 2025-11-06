<!DOCTYPE html>

<html lang="id">

<head>
    
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Manajemen Studio - FilmPass Admin</title>

    <!-- Memuat Tailwind CSS dari CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Memuat Lucide Icons untuk ikon yang bersih -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    <style>
        /* Menggunakan font Inter */
        body { font-family: 'Inter', sans-serif; }
        .sidebar {
            width: 256px; /* Lebar sidebar */
            min-height: 100vh;
        }
        .content {
            flex-grow: 1;
        }
        /* Style kustom untuk progress bar (Tingkat Okupansi) */
        .progress-bar-container {
            height: 8px;
            background-color: #e5e7eb; /* Warna abu-abu terang */
            border-radius: 9999px;
            overflow: hidden;
        }
    </style>

</head>

<body class="bg-gray-50 flex">

    <!-- ========================================================== -->
    <!-- SIDEBAR NAVIGASI -->
    <!-- ========================================================== -->
    <aside class="sidebar bg-white border-r border-gray-200 p-6 flex flex-col justify-between fixed h-full z-10 hidden lg:block">
        <div>
            <!-- Logo dan Nama Aplikasi -->
            <div class="mb-10 flex items-center">
                <span class="text-xl font-bold text-gray-900">FilmPass</span>
            </div>

            <!-- Menu Navigasi -->
            <nav class="space-y-2">
                <a href="/admin/dashboard" class="flex items-center p-3 text-gray-600 hover:bg-gray-100 rounded-lg transition duration-150">
                    <i data-lucide="layout-dashboard" class="w-5 h-5 mr-3"></i>
                    Dashboard
                </a>
                <a href="#" class="flex items-center p-3 text-gray-600 hover:bg-gray-100 rounded-lg transition duration-150">
                    <i data-lucide="film" class="w-5 h-5 mr-3"></i>
                    Manajemen Film
                </a>
                <!-- MENU AKTIF -->
                <a href="#" class="flex items-center p-3 text-indigo-700 bg-indigo-50 font-semibold rounded-lg">
                    <i data-lucide="monitor" class="w-5 h-5 mr-3"></i>
                    Manajemen Studio
                </a>
                <a href="#" class="flex items-center p-3 text-gray-600 hover:bg-gray-100 rounded-lg transition duration-150">
                    <i data-lucide="calendar" class="w-5 h-5 mr-3"></i>
                    Jadwal Tayang
                </a>
                <a href="#" class="flex items-center p-3 text-gray-600 hover:bg-gray-100 rounded-lg transition duration-150">
                    <i data-lucide="ticket" class="w-5 h-5 mr-3"></i>
                    Pemesanan
                </a>
                <a href="#" class="flex items-center p-3 text-gray-600 hover:bg-gray-100 rounded-lg transition duration-150">
                    <i data-lucide="bar-chart-2" class="w-5 h-5 mr-3"></i>
                    Laporan
                </a>
            </nav>
        </div>
    </aside>

    <!-- ========================================================== -->
    <!-- KONTEN UTAMA, HEADER, DAN FOOTER -->
    <!-- ========================================================== -->
    <div class="content lg:ml-64 p-0 w-full flex flex-col min-h-screen">

        <!-- HEADER (fixed di atas) -->
        <header class="bg-white p-4 border-b border-gray-200 flex justify-end items-center fixed top-0 w-full lg:w-[calc(100%-256px)] z-20 shadow-sm">
            <div class="flex items-center space-x-4">
                <button class="text-gray-500 hover:text-gray-700">
                    <!-- Menggunakan ikon bell Lucide yang lebih baik -->
                    <i data-lucide="bell" class="w-5 h-5"></i>
                </button>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-red-700 hover:bg-red-100 p-2 rounded">Logout</button>
                </form>
            </div>
        </header>

        <!-- AREA UTAMA KONTEN (padding top disesuaikan dengan tinggi header) -->
        <main class="flex-grow pt-20 px-6 pb-6">
            <!-- Navigasi Back ke Dashboard -->
            <div class="mb-4">
                <a href="/admin/dashboard" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-medium py-2 px-4 rounded-lg bg-indigo-50 hover:bg-indigo-100 transition shadow-sm">
                    <i data-lucide="arrow-left" class="w-5 h-5 mr-2"></i>
                    Kembali ke Dashboard
                </a>
            </div>

            <!-- JUDUL HALAMAN -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Manajemen Studio</h1>
                <p class="text-gray-500">Kelola studio bioskop dan fasilitas</p>
            </div>

            <!-- BARIS ATAS (Search, Filter, Tombol Tambah) -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 space-y-4 md:space-y-0">
                
                <!-- Search dan Filter -->
                <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4 w-full md:w-auto">
                    <!-- Search Input -->
                    <div class="relative w-full sm:w-80">
                        <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2"></i>
                        <input 
                            type="text" 
                            id="search-studio"
                            placeholder="Cari studio..." 
                            class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg w-full focus:ring-indigo-600 focus:border-indigo-600"
                            oninput="filterStudios()"
                        >
                    </div>

                    <!-- Filter Dropdown -->
                    <div class="relative">
                        <select 
                            id="filter-tipe"
                            class="appearance-none block w-full bg-white border border-gray-300 py-2 pl-4 pr-8 rounded-lg leading-tight focus:outline-none focus:bg-white focus:border-indigo-600 cursor-pointer"
                            onchange="filterStudios()"
                        >
                            <option value="Semua">Semua Tipe</option>
                            <option value="IMAX">IMAX</option>
                            <option value="VIP">VIP</option>
                            <option value="Regular">Regular</option>
                            <option value="Dolby Atmos">Dolby Atmos</option>
                            <option value="4DX">4DX</option>
                        </select>
                        <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                    </div>
                </div>

                <!-- Tombol Tambah Studio -->
                <button class="bg-indigo-700 hover:bg-indigo-800 text-white font-semibold py-2 px-4 rounded-lg shadow-md flex items-center w-full md:w-auto transition duration-150" onclick="showModal('add')">
                    <i data-lucide="plus" class="w-5 h-5 mr-2"></i>
                    Tambah Studio
                </button>
            </div>


            <!-- GRID KARTU STUDIO -->
            <div id="studio-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <!-- Data studio akan dirender di sini oleh JavaScript -->
            </div>
            
        </main>
        
        <!-- FOOTER BARU (DIMASUKKAN DI DALAM DIV.CONTENT) -->
        <footer class="bg-gray-900 text-white mt-12 pt-12 pb-10 border-t border-gray-700">
            <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-4 gap-10">
                
                <!-- Kolom 1: Logo dan Deskripsi -->
                <div class="md:col-span-2 space-y-4">
                    <div class="text-2xl font-extrabold flex items-center">
                        <i data-lucide="clapperboard" class="w-6 h-6 mr-2 text-indigo-500"></i>
                        <span class="text-white">Film</span><span class="text-indigo-500">Pass</span>
                    </div>
                    <p class="text-gray-400 text-sm max-w-sm">
                        Platform pemesanan tiket bioskop online terpercaya. Nikmati pengalaman menonton film favorit Anda dengan mudah dan nyaman.
                    </p>
                    <div class="flex space-x-4 text-gray-400">
                        <!-- Ikon Media Sosial -->
                        <a href="#" class="hover:text-white transition"><i data-lucide="facebook" class="w-5 h-5"></i></a>
                        <a href="#" class="hover:text-white transition"><i data-lucide="twitter" class="w-5 h-5"></i></a>
                        <a href="#" class="hover:text-white transition"><i data-lucide="instagram" class="w-5 h-5"></i></a>
                        <a href="#" class="hover:text-white transition"><i data-lucide="youtube" class="w-5 h-5"></i></a>
                    </div>
                </div>

                <!-- Kolom 2: Tautan Cepat -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">Tautan Cepat</h4>
                    <nav class="space-y-2 text-sm">
                        <a href="#" class="block text-gray-400 hover:text-indigo-400 transition">Beranda</a>
                        <a href="#" class="block text-gray-400 hover:text-indigo-400 transition">Film</a>
                        <a href="#" class="block text-gray-400 hover:text-indigo-400 transition">Cari Film</a>
                        <a href="#" class="block text-gray-400 hover:text-indigo-400 transition">Tentang Kami</a>
                    </nav>
                </div>

                <!-- Kolom 3: Bantuan -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">Bantuan</h4>
                    <nav class="space-y-2 text-sm">
                        <a href="#" class="block text-gray-400 hover:text-indigo-400 transition">Pusat Bantuan</a>
                        <a href="#" class="block text-gray-400 hover:text-indigo-400 transition">Hubungi Kami</a>
                        <a href="#" class="block text-gray-400 hover:text-indigo-400 transition">Syarat & Ketentuan</a>
                        <a href="#" class="block text-gray-400 hover:text-indigo-400 transition">Kebijakan Privasi</a>
                    </nav>
                </div>

            </div>
            <!-- Bagian Hak Cipta -->
            <div class="max-w-7xl mx-auto px-6 mt-10 border-t border-gray-700 pt-6">
                <p class="text-center text-sm text-gray-500">&copy; 2024 FilmPass. All rights reserved.</p>
            </div>
        </footer>
    </div>
    


    <!-- ========================================================== -->
    <!-- MODAL EDIT/TAMBAH (LOGIC SIMULASI) -->
    <!-- ========================================================== -->
    <div id="studio-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-2xl w-full max-w-lg">
            <h2 id="modal-title" class="text-2xl font-bold mb-4 text-gray-800">Edit Studio X</h2>
            
            <form id="studio-form" onsubmit="saveStudio(event)">
                <input type="hidden" id="modal-studio-id">
                
                <div class="mb-4">
                    <label for="studio-name" class="block text-sm font-medium text-gray-700 mb-1">Nama Studio</label>
                    <input type="text" id="studio-name" required class="w-full border-gray-300 rounded-lg p-2 focus:ring-indigo-600 focus:border-indigo-600 bg-indigo-50">
                </div>

                <div class="mb-4">
                    <label for="studio-capacity" class="block text-sm font-medium text-gray-700 mb-1">Kapasitas Kursi</label>
                    <input type="number" id="studio-capacity" required class="w-full border-gray-300 rounded-lg p-2 focus:ring-indigo-600 focus:border-indigo-600 bg-indigo-50">
                </div>
                
                <div class="mb-4">
                    <label for="studio-price" class="block text-sm font-medium text-gray-700 mb-1">Harga Tiket (Rp)</label>
                    <input type="number" id="studio-price" required class="w-full border-gray-300 rounded-lg p-2 focus:ring-indigo-600 focus:border-indigo-600 bg-indigo-50">
                </div>

                <div class="mb-6">
                    <label for="studio-facilities" class="block text-sm font-medium text-gray-700 mb-1">Fasilitas (pisahkan dengan koma)</label>
                    <textarea id="studio-facilities" rows="3" class="w-full border-gray-300 rounded-lg p-2 focus:ring-indigo-600 focus:border-indigo-600 bg-indigo-50"></textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" class="bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg hover:bg-gray-300 transition" onclick="hideModal()">Batal</button>
                    <!-- WARNA NAVY (INDIGO) DI SINI -->
                    <button type="submit" class="bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg hover:bg-indigo-800 transition">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL KONFIRMASI DELETE (Pengganti confirm()) -->
    <div id="delete-confirmation-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white p-6 rounded-xl shadow-2xl w-full max-w-sm transform transition-all duration-300 scale-100">
            <h2 class="text-xl font-bold mb-4 text-gray-800">Konfirmasi Hapus Studio</h2>
            <p class="mb-6 text-gray-600">Apakah Anda yakin ingin menghapus studio <span id="delete-studio-name" class="font-bold text-red-600"></span>? Aksi ini tidak dapat dibatalkan.</p>
            <div class="flex justify-end space-x-3">
                <button type="button" class="bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg hover:bg-gray-300 transition shadow-sm" onclick="hideDeleteConfirmation()">Batal</button>
                <button type="button" id="confirm-delete-button" class="bg-red-600 text-white font-medium py-2 px-4 rounded-lg hover:bg-red-700 transition shadow-md" onclick="confirmDelete()">Hapus Permanen</button>
            </div>
        </div>
    </div>

    <!-- TOAST NOTIFICATION -->
    <div id="toast-notification" class="fixed bottom-5 right-5 p-4 rounded-lg shadow-xl text-white transition-opacity duration-300 opacity-0 z-50" role="alert">
        <!-- Content here -->
    </div>


    <!-- ========================================================== -->
    <!-- LOGIC JAVASCRIPT -->
    <!-- ========================================================== -->
    <script>
        // Data Dummy Studio (Simulasi data dari Database)
        let studios = [
            { 
                id: 1, 
                name: "Studio 1", 
                type: "IMAX", 
                status: "Aktif", 
                capacity: 300, 
                price: 75000, 
                occupancy: 0.85, 
                description: "Studio IMAX dengan layar raksasa dan sistem suara terbaik",
                facilities: ["IMAX Screen", "Dolby Atmos", "Reclining Seats"]
            },
            { 
                id: 2, 
                name: "Studio 2", 
                type: "VIP", 
                status: "Aktif", 
                capacity: 50, 
                price: 120000, 
                occupancy: 0.92, 
                description: "Studio VIP dengan kursi mewah dan layanan premium",
                facilities: ["Luxury Seats", "Food Service", "Private Lounge"]
            },
            { 
                id: 3, 
                name: "Studio 3", 
                type: "Regular", 
                status: "Aktif", 
                capacity: 200, 
                price: 50000, 
                occupancy: 0.67, 
                description: "Studio Regular dengan fasilitas standar yang nyaman",
                facilities: ["Standard Screen", "Surround Sound", "Comfortable Seats"]
            },
            { 
                id: 4, 
                name: "Studio 4", 
                type: "Dolby Atmos", 
                status: "Maintenance", 
                capacity: 180, 
                price: 65000, 
                occupancy: 0.00, 
                description: "Studio dengan teknologi audio Dolby Atmos terdepan",
                facilities: ["Dolby Atmos", "Premium Screen", "Enhanced Audio"]
            },
            { 
                id: 5, 
                name: "Studio 5", 
                type: "4DX", 
                status: "Aktif", 
                capacity: 120, 
                price: 100000, 
                occupancy: 0.78, 
                description: "Studio 4DX dengan kursi bergerak dan efek lingkungan",
                facilities: ["4DX Motion Seats", "Environmental Effects", "Premium Screen"]
            }
        ];

        // Variable global untuk menyimpan ID studio yang akan dihapus
        let studioToDeleteId = null;

        // Fungsi untuk format mata uang Rupiah
        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(number);
        }

        // FUNGSI TOAST NOTIFICATION (Pengganti alert/console log)
        function showToast(message, type = 'info') {
            const toast = document.getElementById('toast-notification');
            let bgColor;

            switch(type) {
                case 'success': bgColor = 'bg-green-600'; break;
                case 'error': bgColor = 'bg-red-600'; break;
                case 'warning': bgColor = 'bg-yellow-600'; break;
                default: bgColor = 'bg-indigo-600'; break;
            }

            toast.className = `fixed bottom-5 right-5 p-4 rounded-lg shadow-xl text-white transition-opacity duration-300 opacity-0 z-50 ${bgColor}`;
            toast.textContent = message;
            
            // Show toast
            toast.classList.remove('opacity-0');
            toast.classList.add('opacity-100');

            // Hide toast after 3 seconds
            setTimeout(() => {
                toast.classList.remove('opacity-100');
                toast.classList.add('opacity-0');
            }, 3000);
        }


        // Fungsi untuk merender kartu studio ke DOM
        function renderStudios(studioList) {
            const grid = document.getElementById('studio-grid');
            grid.innerHTML = ''; // Kosongkan grid sebelum merender

            if (studioList.length === 0) {
                grid.innerHTML = '<p class="col-span-full text-center text-gray-500 py-10">Tidak ada studio yang ditemukan.</p>';
                lucide.createIcons();
                return;
            }

            studioList.forEach(studio => {
                // Tentukan warna status
                const statusColor = studio.status === 'Aktif' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700';

                // Hitung persentase okupansi
                const occupancyPercent = (studio.occupancy * 100).toFixed(0);
                const progressBarWidth = `${occupancyPercent}%`;

                // Render kartu
                const card = `
                    <div data-id="${studio.id}" data-type="${studio.type}" class="bg-white p-6 rounded-xl shadow-lg border border-gray-100 flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-800">${studio.name}</h3>
                                    <p class="text-sm text-gray-500">${studio.type}</p>
                                </div>
                                <span class="text-xs font-semibold py-1 px-3 rounded-full ${statusColor}">${studio.status}</span>
                            </div>

                            <!-- Detail Statistik -->
                            <div class="space-y-2 mb-4 text-gray-700">
                                <div class="flex justify-between text-sm">
                                    <span>Kapasitas</span>
                                    <span class="font-medium text-gray-900">${studio.capacity} kursi</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <!-- WARNA NAVY (INDIGO) DI SINI -->
                                    <span>Harga Tiket</span>
                                    <span class="font-medium text-indigo-700">${formatRupiah(studio.price)}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span>Okupansi</span>
                                    <span class="font-medium">${occupancyPercent}%</span>
                                </div>
                            </div>
                            
                            <!-- Progress Bar Okupansi -->
                            <div class="mb-4">
                                <span class="text-xs text-gray-500">Tingkat Okupansi</span>
                                <div class="progress-bar-container mt-1">
                                    <!-- WARNA NAVY (INDIGO) DI SINI -->
                                    <div class="h-full bg-indigo-700 transition-all duration-500" style="width: ${progressBarWidth};"></div>
                                </div>
                            </div>

                            <!-- Deskripsi Studio -->
                            <!-- WARNA NAVY (INDIGO) DI SINI -->
                            <p class="text-xs text-gray-600 italic mb-4 border-l-2 border-indigo-500 pl-3">${studio.description}</p>
                            
                            <!-- Fasilitas -->
                            <div>
                                <h4 class="text-sm font-semibold mb-2">Fasilitas</h4>
                                <div class="flex flex-wrap gap-2">
                                    ${studio.facilities.map(fac => `<span class="text-xs text-gray-600 bg-gray-100 py-1 px-2 rounded-full">${fac}</span>`).join('')}
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="flex justify-between items-center mt-6 border-t pt-4">
                            <button onclick="showModal('edit', ${studio.id})" class="text-sm text-blue-600 hover:text-blue-800 flex items-center transition">
                                <i data-lucide="square-pen" class="w-4 h-4 mr-1"></i>
                                Edit
                            </button>
                            <div class="flex space-x-3">
                                <button onclick="toggleMaintenance(${studio.id})" class="text-sm text-yellow-600 hover:text-yellow-800 flex items-center transition">
                                    <i data-lucide="wrench" class="w-4 h-4 mr-1"></i>
                                    ${studio.status === 'Aktif' ? 'Maintenance' : 'Aktifkan'}
                                </button>
                                <button onclick="deleteStudio(${studio.id})" class="text-sm text-red-600 hover:text-red-800 flex items-center transition">
                                    <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i>
                                    Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                grid.insertAdjacentHTML('beforeend', card);
            });
            // Memastikan ikon Lucide dirender ulang setelah penambahan elemen baru
            lucide.createIcons(); 
        }

        // Fungsi Filter dan Search (Logic Simulasi)
        function filterStudios() {
            const searchQuery = document.getElementById('search-studio').value.toLowerCase();
            const filterType = document.getElementById('filter-tipe').value;

            const filtered = studios.filter(studio => {
                const matchesSearch = studio.name.toLowerCase().includes(searchQuery) || 
                                      studio.description.toLowerCase().includes(searchQuery);
                
                const matchesType = filterType === 'Semua' || studio.type === filterType;
                
                return matchesSearch && matchesType;
            });

            renderStudios(filtered);
        }

        // FUNGSI MODAL TAMBAH/EDIT
        function showModal(mode, studioId = null) {
            const modal = document.getElementById('studio-modal');
            const form = document.getElementById('studio-form');
            const title = document.getElementById('modal-title');
            
            form.reset();
            
            if (mode === 'add') {
                title.textContent = 'Tambah Studio Baru';
                document.getElementById('modal-studio-id').value = '';
            } else if (mode === 'edit' && studioId) {
                const studio = studios.find(s => s.id === studioId);
                if (studio) {
                    title.textContent = `Edit Studio ${studio.name}`;
                    document.getElementById('modal-studio-id').value = studio.id;
                    document.getElementById('studio-name').value = studio.name;
                    document.getElementById('studio-capacity').value = studio.capacity;
                    document.getElementById('studio-price').value = studio.price;
                    document.getElementById('studio-facilities').value = studio.facilities.join(', ');
                }
            }
            modal.style.display = 'flex';
        }

        function hideModal() {
            document.getElementById('studio-modal').style.display = 'none';
        }

        function saveStudio(event) {
            event.preventDefault();
            
            // Ambil data dari form
            const id = document.getElementById('modal-studio-id').value;
            const name = document.getElementById('studio-name').value;
            const capacity = parseInt(document.getElementById('studio-capacity').value);
            const price = parseInt(document.getElementById('studio-price').value);
            const facilitiesText = document.getElementById('studio-facilities').value;
            const facilities = facilitiesText.split(',').map(f => f.trim()).filter(f => f.length > 0);
            
            let message = '';

            if (id) {
                // EDIT LOGIC (Simulasi Update)
                const index = studios.findIndex(s => s.id === parseInt(id));
                if (index !== -1) {
                    studios[index].name = name;
                    studios[index].capacity = capacity;
                    studios[index].price = price;
                    studios[index].facilities = facilities;
                    message = `Studio ${name} berhasil diperbarui.`;
                }
            } else {
                // ADD LOGIC (Simulasi Tambah Baru)
                // Cari ID terbesar, tambahkan 1. Jika array kosong, mulai dari 1.
                const newId = studios.length > 0 ? Math.max(...studios.map(s => s.id)) + 1 : 1;
                const newStudio = {
                    id: newId,
                    name: name,
                    type: "Regular", // Default type
                    status: "Aktif",
                    capacity: capacity,
                    price: price,
                    occupancy: 0.0,
                    description: "Deskripsi studio baru (Belum ditentukan).",
                    facilities: facilities
                };
                studios.push(newStudio);
                message = `Studio ${name} berhasil ditambahkan.`;
            }

            hideModal();
            filterStudios(); // Render ulang setelah perubahan
            showToast(message, 'success');
        }

        // FUNGSI MODAL KONFIRMASI HAPUS (Pengganti confirm())
        function showDeleteConfirmation(studioId) {
            const studio = studios.find(s => s.id === studioId);
            if (!studio) return;

            studioToDeleteId = studioId;
            document.getElementById('delete-studio-name').textContent = studio.name;
            document.getElementById('delete-confirmation-modal').style.display = 'flex';
        }

        function hideDeleteConfirmation() {
            document.getElementById('delete-confirmation-modal').style.display = 'none';
            studioToDeleteId = null;
        }

        function confirmDelete() {
            if (studioToDeleteId !== null) {
                // Panggil logic hapus utama
                deleteStudio(studioToDeleteId, true);
                hideDeleteConfirmation();
            }
        }
        
        function deleteStudio(studioId, confirmed = false) {
            // Jika belum dikonfirmasi, tampilkan modal konfirmasi
            if (!confirmed) {
                showDeleteConfirmation(studioId);
                return;
            }
            
            // Logic Hapus
            const studioName = studios.find(s => s.id === studioId)?.name || 'Studio';
            studios = studios.filter(s => s.id !== studioId);
            filterStudios();
            showToast(`Studio ${studioName} telah dihapus.`, 'success');
        }
        
        function toggleMaintenance(studioId) {
            const studio = studios.find(s => s.id === studioId);
            if (studio) {
                studio.status = studio.status === 'Aktif' ? 'Maintenance' : 'Aktif';
                filterStudios();
                showToast(`Status Studio ${studio.name} diubah menjadi ${studio.status}.`, 'info');
            }
        }

        // Inisialisasi: Panggil fungsi render saat halaman dimuat
        window.onload = function() {
            renderStudios(studios);
            lucide.createIcons(); // Inisialisasi ikon Lucide
        };
    </script>
</body>
</html>