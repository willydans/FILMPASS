<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>FilmPass - Nikmati Film Favorit Anda</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

{{-- 
  1. MEMANGGIL TAILWIND CDN DENGAN PLUGIN FORM
--}}
<script src="https://cdn.tailwindcss.com?plugins=forms"></script>

{{-- 
  2. MEMANGGIL ALPINE.JS CDN (Untuk carousel autoplay di homepage) 
--}}
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

{{-- 3. Konfigurasi CDN agar menggunakan font 'Inter' --}}
<script>
  tailwind.config = {
    theme: {
      extend: {
        fontFamily: {
          sans: ['Inter', 'sans-serif'],
        },
      }
    }
  }
</script>

{{-- 4. CSS Kustom (jika ada, seperti untuk sembunyikan scrollbar) --}}
<style>
    body {
        font-family: 'Inter', sans-serif;
    }
    
    /* (Opsional) Sembunyikan scrollbar untuk carousel CSS Snap */
    .overflow-x-auto::-webkit-scrollbar {
        display: none;
    }
    .overflow-x-auto {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>