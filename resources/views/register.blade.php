<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar - UNIMARCH</title>
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
        .card {
            width: 100%;
            max-width: 420px;
            background: #fff;
            padding: 36px 32px 32px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }
        .brand { text-align: center; margin-bottom: 24px; }
        .brand img { height: 70px; width: auto; margin-bottom: 12px; }
        .brand h1 { margin: 0; font-size: 26px; letter-spacing: 1px; color: #1e3a8a; }
        .brand .tagline { margin: 6px 0 0; font-size: 13px; font-weight: bold; color: #475569; }
        .brand .desc { margin: 4px 0 0; font-size: 13px; color: #64748b; }
        label { display: block; margin-bottom: 6px; font-size: 14px; color: #334155; }
        input[type=text], input[type=email], input[type=password], select {
            width: 100%;
            padding: 10px 12px;
            margin-bottom: 16px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 14px;
            background: #fff;
        }
        .pw-wrap { position: relative; margin-bottom: 16px; }
        .pw-wrap input { margin-bottom: 0; padding-right: 44px; }
        .pw-wrap .toggle-pw {
            position: absolute;
            right: 6px;
            top: 50%;
            transform: translateY(-50%);
            width: auto;
            padding: 6px;
            background: transparent;
            color: #64748b;
            border: 0;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
        }
        .pw-wrap .toggle-pw:hover { background: transparent; color: #1e3a8a; }
        button[type=submit] {
            width: 100%;
            padding: 11px;
            background: #1e3a8a;
            color: #fff;
            border: 0;
            border-radius: 8px;
            font-size: 15px;
            cursor: pointer;
        }
        button[type=submit]:hover { background: #1e40af; }
        .error { margin-bottom: 16px; padding: 10px 12px; background: #fee2e2; color: #b91c1c; border-radius: 8px; font-size: 14px; }
        .error ul { margin: 0; padding-left: 18px; }
        .switch { margin-top: 16px; text-align: center; font-size: 14px; color: #64748b; }
        .switch a { color: #1e3a8a; }
        .footer { margin-top: 24px; font-size: 12px; color: #94a3b8; text-align: center; }
    </style>
</head>
<body>
    <div class="card">
        <div class="brand">
            <img src="{{ asset('images/logo-unima.png') }}" alt="Logo UNIMA" onerror="this.style.display='none'">
            <h1>UNIMARCH</h1>
            <p class="tagline">UNIMA Archive Management</p>
            <p class="desc">Daftar akun pengirim surat</p>
        </div>

        @if ($errors->any())
            <div class="error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="/daftar">
            @csrf

            <label for="name">Nama lengkap / nama instansi</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>

            <label for="no_telp">No. telepon (opsional)</label>
            <input type="text" id="no_telp" name="no_telp" value="{{ old('no_telp') }}">

            <label for="tipe_pengirim">Mendaftar sebagai</label>
            <select id="tipe_pengirim" name="tipe_pengirim" required>
                <option value="">-- Pilih --</option>
                <option value="mahasiswa" @selected(old('tipe_pengirim') === 'mahasiswa')>Mahasiswa</option>
                <option value="instansi" @selected(old('tipe_pengirim') === 'instansi')>Instansi</option>
            </select>

            <label for="password">Password (minimal 8 karakter)</label>
            <div class="pw-wrap">
                <input type="password" id="password" name="password" required>
                <button type="button" class="toggle-pw" onclick="togglePw('password', this)" aria-label="Tampilkan atau sembunyikan password">
                    <svg class="eye" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    <svg class="eye-off" style="display:none" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                </button>
            </div>

            <label for="password_confirmation">Ulangi password</label>
            <div class="pw-wrap">
                <input type="password" id="password_confirmation" name="password_confirmation" required>
                <button type="button" class="toggle-pw" onclick="togglePw('password_confirmation', this)" aria-label="Tampilkan atau sembunyikan password">
                    <svg class="eye" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    <svg class="eye-off" style="display:none" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                </button>
            </div>

            <button type="submit">Daftar</button>
        </form>

        <div class="switch">Sudah punya akun? <a href="/login">Masuk</a></div>
    </div>

    <div class="footer">&copy; 2026 Perpustakaan UNIMA. All rights reserved.</div>

    <script>
        function togglePw(id, btn) {
            var input = document.getElementById(id);
            var show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            btn.querySelector('.eye').style.display = show ? 'none' : 'block';
            btn.querySelector('.eye-off').style.display = show ? 'block' : 'none';
        }
    </script>
</body>
</html>