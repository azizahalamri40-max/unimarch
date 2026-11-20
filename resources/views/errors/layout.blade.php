<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('kode') - UNIMARCH</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: #f1f5f9;
            font-family: Arial, sans-serif;
            padding: 24px 16px;
        }
        .box {
            width: 100%;
            max-width: 440px;
            background: #fff;
            padding: 40px 32px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            text-align: center;
        }
        .box img { height: 64px; width: auto; margin-bottom: 12px; }
        .kode { font-size: 56px; font-weight: bold; color: #1e3a8a; line-height: 1; }
        h1 { margin: 12px 0 8px; font-size: 20px; color: #1e293b; }
        p { margin: 0 0 24px; font-size: 14px; color: #64748b; line-height: 1.5; }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #1e3a8a;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            font-size: 14px;
        }
        .btn:hover { background: #1e40af; }
        .footer { margin-top: 24px; font-size: 12px; color: #94a3b8; text-align: center; }
    </style>
</head>
<body>
    <div class="box">
        <img src="{{ asset('images/logo-unima.png') }}" alt="Logo UNIMA" onerror="this.style.display='none'">
        <div class="kode">@yield('kode')</div>
        <h1>@yield('judul')</h1>
        <p>@yield('pesan')</p>
        <a href="{{ url('/') }}" class="btn">Kembali ke beranda</a>
    </div>

    <div class="footer">&copy; 2026 Perpustakaan UNIMA. All rights reserved.</div>
</body>
</html>