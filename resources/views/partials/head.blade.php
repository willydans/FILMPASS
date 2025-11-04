<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>FilmPass - Nikmati Film Favorit Anda</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

{{-- 
  1. HAPUS BARIS @vite DI BAWAH INI (ATAU BERI KOMENTAR) 
--}}
{{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}

{{-- 
  2. GANTI DENGAN SCRIPT TAILWIND CDN 
--}}
<script src="https://cdn.tailwindcss.com"></script>

{{-- 
  3. (Opsional) Konfigurasi CDN agar menggunakan font Inter 
     yang sudah kita panggil di atas.
--}}
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