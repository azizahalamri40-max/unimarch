<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') - UNIMARCH</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: Arial, sans-serif; background: #f1f5f9; color: #1e293b; }
        .layout { display: flex; min-height: 100vh; }

        .sidebar {
            width: 250px;
            background: #1e3a8a;
            color: #fff;
            display: flex;
            flex-direction: column;
            padding: 24px 16px;
        }
        .sidebar .brand { text-align: center; margin-bottom: 28px; }
        .sidebar .brand img { height: 56px; width: auto; margin-bottom: 8px; }
        .sidebar .brand h2 { margin: 0; font-size: 20px; letter-spacing: 1px; }
        .sidebar .brand p { margin: 4px 0 0; font-size: 12px; color: #bfdbfe; }

        .menu { flex: 1; }
        .menu a {
            display: block;
            padding: 10px 14px;
            margin-bottom: 4px;
            color: #dbeafe;
            text-decoration: none;
            border-radius: 8px;
            font-size: 14px;
        }
        .menu a:hover, .menu a.active { background: #1e40af; color: #fff; }

        .user-box { border-top: 1px solid #3b5bb5; padding-top: 16px; font-size: 13px; }
        .user-box .name { font-weight: bold; }
        .user-box .role { color: #bfdbfe; margin-bottom: 12px; text-transform: capitalize; }
        .user-box button {
            width: 100%;
            padding: 9px;
            background: #dc2626;
            color: #fff;
            border: 0;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
        }
        .user-box button:hover { background: #b91c1c; }

        .content { flex: 1; padding: 32px; min-width: 0; }
        .content h1 { margin: 0 0 20px; font-size: 24px; color: #1e3a8a; }
        .panel { background: #fff; padding: 24px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.06); }
        .footer { margin-top: 24px; font-size: 12px; color: #94a3b8; }

        /* Warna badge status */
        .badge.s-dikirim     { background: #e2e8f0; color: #334155; }
        .badge.s-diterima    { background: #dbeafe; color: #1e3a8a; }
        .badge.s-diteruskan  { background: #fef3c7; color: #92400e; }
        .badge.s-didisposisi { background: #ede9fe; color: #5b21b6; }
        .badge.s-diproses    { background: #cffafe; color: #155e75; }
        .badge.s-selesai     { background: #dcfce7; color: #166534; }
    </style>
</head>
<body>
<div class="layout">
    <aside class="sidebar">
        <div class="brand">
            <img src="{{ asset('images/logo-unima.png') }}" alt="Logo UNIMA" onerror="this.style.display='none'">
            <h2>UNIMARCH</h2>
            <p>UNIMA Archive Management</p>
        </div>

        <nav class="menu">
            @if (auth()->user()->role === 'pengirim')
                <a href="/pengirim/dashboard" class="{{ request()->is('pengirim/dashboard') ? 'active' : '' }}">Dashboard</a>
                <a href="/pengirim/surat" class="{{ request()->is('pengirim/surat') ? 'active' : '' }}">Surat Saya</a>
                <a href="/pengirim/surat/baru" class="{{ request()->is('pengirim/surat/baru') ? 'active' : '' }}">Kirim Surat</a>
            @elseif (auth()->user()->role === 'staff')
                <a href="/staff/dashboard" class="{{ request()->is('staff/dashboard') ? 'active' : '' }}">Dashboard</a>
                <a href="/staff/surat" class="{{ request()->is('staff/surat*') ? 'active' : '' }}">Surat Masuk</a>
                <a href="/staff/arsip" class="{{ request()->is('staff/arsip*') ? 'active' : '' }}">Arsip Surat</a>
            @elseif (auth()->user()->role === 'pimpinan')
                <a href="/pimpinan/dashboard" class="{{ request()->is('pimpinan/dashboard') ? 'active' : '' }}">Dashboard</a>
                <a href="/pimpinan/surat" class="{{ request()->is('pimpinan/surat*') ? 'active' : '' }}">Surat Disposisi</a>
                <a href="/pimpinan/arsip" class="{{ request()->is('pimpinan/arsip*') ? 'active' : '' }}">Arsip Surat</a>
            @endif

            <a href="/pengaturan" class="{{ request()->is('pengaturan*') ? 'active' : '' }}">Pengaturan</a>
        </nav>

        <div class="user-box">
            <div class="name">{{ auth()->user()->name }}</div>
            <div class="role">{{ auth()->user()->role }}</div>
            <form method="POST" action="/logout">
                @csrf
                <button type="submit">Keluar</button>
            </form>
        </div>
    </aside>

    <main class="content">
        @yield('content')
        <div class="footer">&copy; 2026 Perpustakaan UNIMA. All rights reserved.</div>
    </main>
</div>

<script>
    // Beri warna pada semua badge status sesuai tulisannya
    document.querySelectorAll('.badge').forEach(function (el) {
        el.classList.add('s-' + el.textContent.trim().toLowerCase());
    });
</script>
</body>
</html>