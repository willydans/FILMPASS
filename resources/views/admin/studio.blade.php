<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Studio - FilmPass Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-100">

    <div class="flex min-h-screen">
        {{-- SIDEBAR --}}
        @include('partials.admin_sidebar')

        {{-- MAIN CONTENT --}}
        <div class="flex-1 flex flex-col">
            {{-- HEADER --}}
            @include('partials.admin_header')

            {{-- CONTENT AREA --}}
            <main class="flex-grow p-6">
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

            {{-- FOOTER --}}
            @include('partials.admin_footer')
        </div>
    </div>

    <!-- MODALS -->
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
                    <button type="submit" class="bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg hover:bg-indigo-800 transition">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <div id="delete-confirmation-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white p-6 rounded-xl shadow-2xl w-full max-w-sm transform transition-all duration-300 scale-100">
            <h2 class="text-xl font-bold mb-4 text-gray-800">Konfirmasi Hapus</h2>
            <p class="mb-6 text-gray-600">Apakah Anda yakin ingin menghapus studio <span id="delete-studio-name" class="font-bold text-red-600"></span>? Aksi ini tidak dapat dibatalkan.</p>
            <div class="flex justify-end space-x-3">
                <button type="button" class="bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg hover:bg-gray-300 transition shadow-sm" onclick="hideDeleteConfirmation()">Batal</button>
                <button type="button" id="confirm-delete-button" class="bg-red-600 text-white font-medium py-2 px-4 rounded-lg hover:bg-red-700 transition shadow-md" onclick="confirmDelete()">Hapus</button>
            </div>
        </div>
    </div>

    <div id="toast-notification" class="fixed bottom-5 right-5 p-4 rounded-lg shadow-xl text-white transition-opacity duration-300 opacity-0 z-50" role="alert">
    </div>

    <script>
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

let studioToDeleteId = null;

function formatRupiah(number) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(number);
}

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
    
    toast.classList.remove('opacity-0');
    toast.classList.add('opacity-100');

    setTimeout(() => {
        toast.classList.remove('opacity-100');
        toast.classList.add('opacity-0');
    }, 3000);
}

function renderStudios(studioList) {
    const grid = document.getElementById('studio-grid');
    grid.innerHTML = '';

    if (studioList.length === 0) {
        grid.innerHTML = '<p class="col-span-full text-center text-gray-500 py-10">Tidak ada studio yang ditemukan.</p>';
        lucide.createIcons();
        return;
    }

    studioList.forEach(studio => {
        const statusColor = studio.status === 'Aktif' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700';
        const occupancyPercent = (studio.occupancy * 100).toFixed(0);
        const progressBarWidth = `${occupancyPercent}%`;

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

                    <div class="space-y-2 mb-4 text-gray-700">
                        <div class="flex justify-between text-sm">
                            <span>Kapasitas</span>
                            <span class="font-medium text-gray-900">${studio.capacity} kursi</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span>Harga Tiket</span>
                            <span class="font-medium text-indigo-700">${formatRupiah(studio.price)}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span>Okupansi</span>
                            <span class="font-medium">${occupancyPercent}%</span>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <span class="text-xs text-gray-500">Tingkat Okupansi</span>
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                            <div class="bg-indigo-600 h-2 rounded-full" style="width: ${progressBarWidth}"></div>
                        </div>
                    </div>

                    <p class="text-xs text-gray-600 italic mb-4 border-l-2 border-indigo-500 pl-3">${studio.description}</p>
                    
                    <div>
                        <h4 class="text-sm font-semibold mb-2">Fasilitas</h4>
                        <div class="flex flex-wrap gap-2">
                            ${studio.facilities.map(fac => `<span class="text-xs text-gray-600 bg-gray-100 py-1 px-2 rounded-full">${fac}</span>`).join('')}
                        </div>
                    </div>
                </div>

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
    lucide.createIcons(); 
}

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
    
    const id = document.getElementById('modal-studio-id').value;
    const name = document.getElementById('studio-name').value;
    const capacity = parseInt(document.getElementById('studio-capacity').value);
    const price = parseInt(document.getElementById('studio-price').value);
    const facilitiesText = document.getElementById('studio-facilities').value;
    const facilities = facilitiesText.split(',').map(f => f.trim()).filter(f => f.length > 0);
    
    let message = '';

    if (id) {
        const index = studios.findIndex(s => s.id === parseInt(id));
        if (index !== -1) {
            studios[index].name = name;
            studios[index].capacity = capacity;
            studios[index].price = price;
            studios[index].facilities = facilities;
            message = `Studio ${name} berhasil diperbarui.`;
        }
    } else {
        const newId = studios.length > 0 ? Math.max(...studios.map(s => s.id)) + 1 : 1;
        const newStudio = {
            id: newId,
            name: name,
            type: "Regular",
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
    filterStudios();
    showToast(message, 'success');
}

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
        deleteStudio(studioToDeleteId, true);
        hideDeleteConfirmation();
    }
}

function deleteStudio(studioId, confirmed = false) {
    if (!confirmed) {
        showDeleteConfirmation(studioId);
        return;
    }
    
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

window.onload = function() {
    renderStudios(studios);
    lucide.createIcons();
};
    </script>
</body>
</html>