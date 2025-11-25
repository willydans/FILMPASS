{{--
    HEADER
    'absolute' untuk menimpa gambar hero. 'z-10' untuk memastikan di atas konten.
--}}
<header class="absolute top-0 left-0 right-0 z-10 bg-slate-900/80 backdrop-blur-md border-b border-slate-700/50">
    <nav class="container mx-auto px-4 sm:px-6 lg:px-8 py-5 flex justify-between items-center">

        {{-- Logo --}}
        <a href="/" class="text-3xl font-extrabold text-white">
            <span class="text-blue-500">Film</span>Pass
        </a>

        {{-- Menu Desktop (Sembunyikan di layar kecil) --}}
        <div class="hidden md:flex space-x-8">
            <a href="/" class="text-gray-300 hover:text-white transition-colors">Beranda</a>
            <a href="/movies" class="text-gray-300 hover:text-white transition-colors">Film</a>

            {{-- Tampilkan menu ini hanya jika user SUDAH login --}}
            @auth
                <a href="{{ route('riwayat')}}" class="text-gray-300 hover:text-white transition-colors">Riwayat Pesanan</a>
            @endauth
        </div>

        <div class="flex items-center space-x-4">
            {{-- Tombol Auth Desktop (Sembunyikan di layar kecil) --}}
            <div class="hidden md:flex items-center space-x-4">
                @guest
                    <a href="{{ route('login') }}" class="text-gray-300 hover:text-white transition-colors font-medium">Masuk</a>
                    <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        Daftar
                    </a>
                @endguest

                @auth
                    {{-- Form Logout dengan ID untuk SweetAlert --}}
                    <form id="logout-form-desktop" action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="button" onclick="confirmLogout('logout-form-desktop')" 
                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            Logout
                        </button>
                    </form>
                @endauth
            </div>

            {{-- Tombol Hamburger (Hanya terlihat di layar kecil) --}}
            <button id="menu-button" class="md:hidden text-white focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>
    </nav>
</header>

{{--
    SIDEBAR (Mobile Menu)
    Default: 'translate-x-full' untuk tersembunyi di kanan.
    Saat aktif: class 'translate-x-full' dihapus oleh JS.
--}}
<div id="sidebar" class="fixed top-0 right-0 h-full w-64 bg-slate-900 z-50 transform translate-x-full transition-transform duration-300 ease-in-out md:hidden shadow-2xl">
    <div class="p-5 flex justify-end">
        {{-- Tombol Tutup --}}
        <button id="close-button" class="text-white focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <nav class="flex flex-col space-y-4 px-5">
        <a href="/" class="text-gray-300 hover:text-white transition-colors py-2 border-b border-slate-700">Beranda</a>
        <a href="/movies" class="text-gray-300 hover:text-white transition-colors py-2 border-b border-slate-700">Film</a>

        @auth
            <a href="{{ route('riwayat')}}" class="text-gray-300 hover:text-white transition-colors py-2 border-b border-slate-700">Riwayat Pesanan</a>
            
            {{-- Tombol Logout Mobile --}}
            <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" class="w-full pt-4">
                @csrf
                <button type="button" onclick="confirmLogout('logout-form-mobile')" 
                        class="w-full text-left bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    Logout
                </button>
            </form>
        @endauth
        
        @guest
            {{-- Tombol Login/Daftar --}}
            <a href="{{ route('login') }}" class="text-gray-300 hover:text-white transition-colors font-medium pt-4">Masuk</a>
            <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                Daftar
            </a>
        @endguest
    </nav>
</div>

{{-- ========================================
     SWEETALERT2 CDN + CUSTOM JAVASCRIPT
     ======================================== --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* Custom SweetAlert Theme untuk Dark Mode */
    .swal2-popup {
        background: #1e293b !important;
        border: 1px solid #334155 !important;
    }
    
    .swal2-title {
        color: #f1f5f9 !important;
        font-weight: 700 !important;
    }
    
    .swal2-html-container {
        color: #cbd5e1 !important;
    }
    
    .swal2-confirm {
        background-color: #dc2626 !important;
        border: none !important;
        border-radius: 0.5rem !important;
        padding: 0.625rem 1.5rem !important;
        font-weight: 600 !important;
    }
    
    .swal2-confirm:hover {
        background-color: #b91c1c !important;
    }
    
    .swal2-cancel {
        background-color: #475569 !important;
        border: none !important;
        border-radius: 0.5rem !important;
        padding: 0.625rem 1.5rem !important;
        font-weight: 600 !important;
    }
    
    .swal2-cancel:hover {
        background-color: #334155 !important;
    }
    
    .swal2-icon.swal2-warning {
        border-color: #f59e0b !important;
        color: #f59e0b !important;
    }
</style>

<script>
    // ========================================
    // FUNGSI KONFIRMASI LOGOUT
    // ========================================
    function confirmLogout(formId) {
        Swal.fire({
            title: 'Yakin ingin keluar?',
            text: "Anda akan keluar dari akun ini",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Keluar',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            backdrop: true,
            allowOutsideClick: true,
            customClass: {
                popup: 'rounded-2xl',
                confirmButton: 'mx-2',
                cancelButton: 'mx-2'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Tampilkan loading
                Swal.fire({
                    title: 'Logging out...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Submit form logout
                document.getElementById(formId).submit();
            }
        });
    }

    // ========================================
    // SIDEBAR TOGGLE (Mobile Menu)
    // ========================================
    document.addEventListener('DOMContentLoaded', () => {
        const menuButton = document.getElementById('menu-button');
        const closeButton = document.getElementById('close-button');
        const sidebar = document.getElementById('sidebar');

        // Fungsi untuk menampilkan sidebar
        menuButton.addEventListener('click', () => {
            sidebar.classList.remove('translate-x-full');
        });

        // Fungsi untuk menyembunyikan sidebar
        closeButton.addEventListener('click', () => {
            sidebar.classList.add('translate-x-full');
        });

        // Tutup sidebar saat klik di luar (optional)
        document.addEventListener('click', (e) => {
            if (!sidebar.contains(e.target) && !menuButton.contains(e.target)) {
                sidebar.classList.add('translate-x-full');
            }
        });
    });

    // ========================================
    // ALERT SUKSES LOGOUT (Jika ada session flash)
    // ========================================
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            timer: 2000,
            showConfirmButton: false,
            customClass: {
                popup: 'rounded-2xl'
            }
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: '{{ session('error') }}',
            confirmButtonText: 'OK',
            customClass: {
                popup: 'rounded-2xl'
            }
        });
    @endif
</script>