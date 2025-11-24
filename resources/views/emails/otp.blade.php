<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode Verifikasi FilmPass</title>
    <style>
        /* Reset Style sederhana */
        body {
            margin: 0;
            padding: 0;
            background-color: #f3f4f6; /* gray-100 */
            font-family: 'Inter', Arial, sans-serif;
            color: #1f2937; /* gray-800 */
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            margin-top: 40px;
            margin-bottom: 40px;
        }
        .header {
            background-color: #1e293b; /* slate-900 (Sesuai tema web Anda) */
            padding: 20px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 24px;
            font-weight: 800;
        }
        .header h1 span {
            color: #4f46e5; /* indigo-600 (Aksen FilmPass) */
        }
        .content {
            padding: 30px;
            text-align: center;
        }
        .otp-code {
            background-color: #f3f4f6;
            color: #4f46e5;
            font-size: 36px;
            font-weight: bold;
            letter-spacing: 8px;
            padding: 15px 0;
            margin: 20px 0;
            border-radius: 6px;
            border: 2px dashed #c7d2fe; /* indigo-200 */
        }
        .footer {
            background-color: #f9fafb;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4f46e5;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        
        {{-- Header --}}
        <div class="header">
            <h1><span>Film</span>Pass</h1>
        </div>

        {{-- Konten Utama --}}
        <div class="content">
            <h2 style="margin-top: 0; color: #111827;">Verifikasi Masuk</h2>
            
            <p style="font-size: 16px; line-height: 1.5; color: #4b5563;">
                Halo! Kami mendeteksi permintaan masuk ke akun FilmPass Anda.
                Gunakan kode verifikasi berikut untuk melanjutkan:
            </p>

            <div class="otp-code">
                {{ $otp }}
            </div>

            <p style="font-size: 14px; color: #6b7280;">
                Kode ini hanya berlaku selama <strong>5 menit</strong>. <br>
                Jangan berikan kode ini kepada siapa pun, termasuk pihak FilmPass.
            </p>
            
            <p style="font-size: 14px; color: #6b7280; margin-top: 30px;">
                Jika Anda tidak merasa melakukan permintaan ini, silakan abaikan email ini.
            </p>
        </div>

        {{-- Footer --}}
        <div class="footer">
            &copy; {{ date('Y') }} FilmPass. Semua hak cipta dilindungi.<br>
            Pesan ini dikirim secara otomatis, mohon jangan dibalas.
        </div>
    </div>
</body>
</html>