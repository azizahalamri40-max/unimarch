@extends('layouts.app')

@section('title', 'Pengaturan')

@section('content')
    <style>
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
        @media (max-width: 900px) { .grid { grid-template-columns: 1fr; } }
        .panel h2 { margin: 0 0 14px; font-size: 17px; color: #1e3a8a; }
        label { display: block; margin-bottom: 6px; font-size: 14px; color: #334155; }
        input[type=text], input[type=email], input[type=password] {
            width: 100%; padding: 10px 12px; margin-bottom: 14px; border: 1px solid #cbd5e1;
            border-radius: 8px; font-size: 14px; background: #fff; font-family: inherit;
        }
        .btn { display: inline-block; padding: 10px 18px; background: #1e3a8a; color: #fff; border: 0; border-radius: 8px; font-size: 14px; cursor: pointer; }
        .btn:hover { background: #1e40af; }
        .alert-success { margin-bottom: 16px; padding: 10px 14px; background: #dcfce7; color: #166534; border-radius: 8px; font-size: 14px; }
        .alert-error { margin-bottom: 16px; padding: 10px 14px; background: #fee2e2; color: #b91c1c; border-radius: 8px; font-size: 14px; }
        .alert-error ul { margin: 0; padding-left: 18px; }
        .info { width: 100%; font-size: 14px; border-collapse: collapse; }
        .info td { padding: 8px 0; vertical-align: top; border-bottom: 1px solid #f1f5f9; }
        .info td:first-child { width: 160px; color: #64748b; }
        .badge { display: inline-block; padding: 3px 10px; border-radius: 999px; background: #dbeafe; color: #1e3a8a; font-size: 12px; text-transform: capitalize; }
        .hint { margin: -8px 0 14px; font-size: 12px; color: #94a3b8; }
    </style>

    <h1>Pengaturan</h1>

    <div class="grid">
        {{-- Profil --}}
        <div class="panel">
            <h2>Profil</h2>

            @if (session('success_profil'))
                <div class="alert-success">{{ session('success_profil') }}</div>
            @endif
            @if ($errors->getBag('profil')->any())
                <div class="alert-error">
                    <ul>
                        @foreach ($errors->getBag('profil')->all() as $pesan)
                            <li>{{ $pesan }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="/pengaturan/profil">
                @csrf

                <label for="name">Nama</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>

                <label for="no_telp">Nomor telepon</label>
                <input type="text" id="no_telp" name="no_telp" value="{{ old('no_telp', $user->no_telp) }}" placeholder="Contoh: 081234567890">

                <button type="submit" class="btn">Simpan profil</button>
            </form>
        </div>

        {{-- Ubah password --}}
        <div class="panel">
            <h2>Ubah Password</h2>

            @if (session('success_password'))
                <div class="alert-success">{{ session('success_password') }}</div>
            @endif
            @if ($errors->getBag('password')->any())
                <div class="alert-error">
                    <ul>
                        @foreach ($errors->getBag('password')->all() as $pesan)
                            <li>{{ $pesan }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="/pengaturan/password">
                @csrf

                <label for="password_lama">Password lama</label>
                <input type="password" id="password_lama" name="password_lama" required autocomplete="current-password">

                <label for="password">Password baru</label>
                <input type="password" id="password" name="password" required autocomplete="new-password">
                <div class="hint">Minimal 8 karakter.</div>

                <label for="password_confirmation">Ulangi password baru</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password">

                <button type="submit" class="btn">Ubah password</button>
            </form>
        </div>
    </div>

    {{-- Info akun --}}
    <div class="panel">
        <h2>Info Akun</h2>
        <table class="info">
            <tr><td>Peran</td><td><span class="badge">{{ $user->role }}</span></td></tr>
            @if ($user->role === 'pengirim')
                <tr><td>Tipe pengirim</td><td>{{ ucfirst($user->tipe_pengirim ?? '-') }}</td></tr>
            @endif
            <tr><td>Terdaftar sejak</td><td>{{ $user->created_at ? $user->created_at->format('d/m/Y') : '-' }}</td></tr>
        </table>
    </div>
@endsection