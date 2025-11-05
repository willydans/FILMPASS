<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>FilmPass - Nikmati Film Favorit Anda</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<script src="https://cdn.tailwindcss.com"></script>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

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

<style>
    /* Mengatur style body dasar agar sesuai gambar */
    body {
        background-color: #0F172A; /* slate-900 */
        color: #F8FAFC; /* slate-50 */
        font-family: 'Inter', sans-serif;
    }

    /* Kita TIDAK LAGI menyembunyikan scrollbar, 
      karena kita akan menggantinya dengan 'overflow-hidden' 
      dan mengontrolnya via Alpine.js 
    */
</style>