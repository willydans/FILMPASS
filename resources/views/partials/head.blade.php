<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

{{-- Judul Halaman Dinamis --}}
<title>{{ $title ?? 'FilmPass - Aplikasi Bioskop' }}</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

{{-- 
  2. TAILWIND CSS CDN
  PENTING: ?plugins=forms wajib ada agar input form terlihat rapi
--}}
<script src="https://cdn.tailwindcss.com?plugins=forms"></script>

{{-- 
  3. KONFIGURASI TAILWIND
  Kita definisikan font dan warna 'indigo' secara manual di sini 
  agar sesuai dengan desain Admin Panel Anda.
--}}
<script>
  tailwind.config = {
    theme: {
      extend: {
        fontFamily: {
          sans: ['Inter', 'sans-serif'],
        },
        colors: {
            // Warna spesifik untuk Admin Panel FilmPass
            indigo: {
                50: '#eff6ff',
                100: '#dbeafe',
                500: '#6366f1',
                600: '#4f46e5',
                700: '#4338ca',
            }
        }
      }
    }
  }
</script>

{{-- 
  4. LIBRARY TAMBAHAN (WAJIB)
  - Alpine.js: Untuk interaksi modal dan dropdown
  - Lucide Icons: Untuk ikon-ikon di sidebar admin
--}}
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

{{-- 5. CSS GLOBAL TAMBAHAN --}}
<style>
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f9fafb; /* bg-gray-50 */
    }
    
    /* Menyembunyikan Scrollbar tapi tetap bisa discroll (Untuk Carousel) */
    .overflow-x-auto::-webkit-scrollbar {
        display: none;
    }
    .overflow-x-auto {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    /* Style untuk Progress Bar di Dashboard Admin */
    .sales-bar-container { 
        height: 8px; 
        border-radius: 9999px; 
        background-color: #e5e7eb; 
        overflow: hidden; 
    }
    .sales-bar-fill { 
        height: 100%; 
        background-color: #4f46e5; 
        transition: width 1s ease-in-out; 
    }
    
    /* Style untuk kotak ikon di Dashboard */
    .icon-box { 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        width: 44px; 
        height: 44px; 
        border-radius: 9999px; 
        opacity: 0.9; 
    }
    
    /* Perbaikan posisi ikon Lucide */
    .lucide { vertical-align: middle; }
</style>