@extends('layouts.app')

@section('title', 'Detail Surat')

@section('content')
    <style>
        .back { display: inline-block; margin-bottom: 12px; color: #1e3a8a; text-decoration: none; font-size: 14px; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        @media (max-width: 900px) { .grid { grid-template-columns: 1fr; } }
        .panel h2 { margin: 0 0 14px; font-size: 17px; color: #1e3a8a; }
        .info { width: 100%; font-size: 14px; border-collapse: collapse; }
        .info td { padding: 6px 0; vertical-align: top; }
        .info td:first-child { width: 140px; color: #64748b; }
        .badge { display: inline-block; padding: 3px 10px; border-radius: 999px; background: #dbeafe; color: #1e3a8a; font-size: 12px; }
        label { display: block; margin-bottom: 6px; font-size: 14px; color: #334155; }
        select, textarea { width: 100%; padding: 10px 12px; margin-bottom: 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; background: #fff; font-family: inherit; }
        .btn { display: inline-block; padding: 10px 18px; background: #1e3a8a; color: #fff; border: 0; border-radius: 8px; font-size: 14px; cursor: pointer; text-decoration: none; }
        .btn:hover { background: #1e40af; }
        .alert-success { margin-bottom: 16px; padding: 10px 14px; background: #dcfce7; color: #166534; border-radius: 8px; font-size: 14px; }
        .alert-error { margin-bottom: 16px; padding: 10px 14px; background: #fee2e2; color: #b91c1c; border-radius: 8px; font-size: 14px; }
        .lock-note { padding: 12px 14px; background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 8px; color: #475569; font-size: 14px; }
        table.tabel { width: 100%; border-collapse: collapse; font-size: 14px; }
        table.tabel th, table.tabel td { padding: 8px 10px; text-align: left; border-bottom: 1px solid #e2e8f0; vertical-align: top; }
        table.tabel th { background: #f8fafc; color: #475569; }
        .kosong { color: #64748b; font-size: 14px; }
        .mt { margin-top: 20px; }
        .agenda-box { display: inline-block; padding: 6px 14px; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; color: #1e3a8a; font-weight: bold; font-size: 16px; letter-spacing: 0.5px; }
        .tbl-wrap { overflow-x: auto; }
    </style>

    <a href="/staff/surat" class="back">&larr; Kembali ke daftar surat</a>
    <h1>Detail Surat</h1>

    @if (session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert-error">{{ $errors->first() }}</div>
    @endif

    <div class="grid">
        <div class="panel">
            <h2>Informasi surat</h2>
            <table class="info">
                <tr><td>No. Agenda</td><td><span class="agenda-box">{{ $surat->nomor_agenda ?? '-' }}</span></td></tr>
                <tr><td>Perihal</td><td>{{ $surat->perihal }}</td></tr>
                <tr><td>Nomor surat</td><td>{{ $surat->nomor_surat ?? '-' }}</td></tr>
                <tr><td>Tanggal surat</td><td>{{ \Carbon\Carbon::parse($surat->tanggal_surat)->format('d/m/Y') }}</td></tr>
                <tr><td>Pengirim</td><td>{{ $surat->pengirim->name }} ({{ ucfirst($surat->pengirim->tipe_pengirim ?? '-') }})</td></tr>
                <tr><td>Email pengirim</td><td>{{ $surat->pengirim->email }}</td></tr>
                <tr><td>No. telepon</td><td>{{ $surat->pengirim->no_telp ?? '-' }}</td></tr>
                <tr><td>Status</td><td><span class="badge">{{ ucfirst($surat->status) }}</span></td></tr>
                <tr><td>File surat</td><td><a href="{{ asset('storage/' . $surat->file_surat) }}" target="_blank">Buka file</a></td></tr>
            </table>
        </div>

        <div class="panel">
            <h2>Ubah status</h2>

            @if ($surat->status === 'didisposisi')
                <div class="lock-note">Surat ini sudah didisposisi pimpinan, jadi statusnya tidak bisa diubah lagi.</div>
            @elseif ($surat->status === 'diteruskan')
                <div class="lock-note">Surat sedang menunggu disposisi pimpinan. Status akan berubah otomatis setelah pimpinan memberi disposisi.</div>
            @else
                <form method="POST" action="/staff/surat/{{ $surat->id }}/status">
                    @csrf

                    <label for="status">Status baru</label>
                    <select id="status" name="status" required>
                        <option value="diterima" @selected($surat->status === 'diterima')>Diterima</option>
                        <option value="diteruskan" @selected($surat->status === 'diteruskan')>Diteruskan ke pimpinan</option>
                    </select>

                    <label for="keterangan">Keterangan (opsional)</label>
                    <textarea id="keterangan" name="keterangan" rows="3"></textarea>

                    <button type="submit" class="btn">Simpan status</button>
                </form>
            @endif
        </div>
    </div>

    <div class="panel mt">
        <h2>Disposisi pimpinan</h2>
        @if ($surat->disposisi->isEmpty())
            <div class="kosong">Belum ada disposisi untuk surat ini.</div>
        @else
            <div class="tbl-wrap">
                <table class="tabel">
                    <thead>
                        <tr><th>Tanggal</th><th>Pimpinan</th><th>Isi disposisi</th><th>Batas waktu</th></tr>
                    </thead>
                    <tbody>
                        @foreach ($surat->disposisi as $d)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($d->tanggal_disposisi)->format('d/m/Y') }}</td>
                                <td>{{ $d->pimpinan->name }}</td>
                                <td>{{ $d->isi_disposisi }}</td>
                                <td>{{ $d->batas_waktu ? \Carbon\Carbon::parse($d->batas_waktu)->format('d/m/Y') : '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div class="panel mt">
        <h2>Riwayat status</h2>
        <div class="tbl-wrap">
            <table class="tabel">
                <thead>
                    <tr><th>Waktu</th><th>Status</th><th>Keterangan</th></tr>
                </thead>
                <tbody>
                    @foreach ($surat->riwayatStatus as $r)
                        <tr>
                            <td>{{ $r->created_at->format('d/m/Y H:i') }}</td>
                            <td><span class="badge">{{ ucfirst($r->status) }}</span></td>
                            <td>{{ $r->keterangan ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection