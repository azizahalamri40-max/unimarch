@extends('layouts.app')

@section('title', 'Surat Saya')

@section('content')
    <style>
        .top-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .top-bar h1 { margin: 0; }
        .btn { display: inline-block; padding: 10px 16px; background: #1e3a8a; color: #fff; text-decoration: none; border-radius: 8px; font-size: 14px; }
        .btn:hover { background: #1e40af; }
        .btn-sm { display: inline-block; padding: 6px 12px; background: #1e3a8a; color: #fff; text-decoration: none; border-radius: 6px; font-size: 13px; }
        .btn-sm:hover { background: #1e40af; }
        .alert-success { margin-bottom: 16px; padding: 10px 14px; background: #dcfce7; color: #166534; border-radius: 8px; font-size: 14px; }
        table.tabel { width: 100%; border-collapse: collapse; font-size: 14px; }
        table.tabel th, table.tabel td { padding: 10px 12px; text-align: left; border-bottom: 1px solid #e2e8f0; }
        table.tabel th { background: #f8fafc; color: #475569; }
        .badge { display: inline-block; padding: 3px 10px; border-radius: 999px; background: #dbeafe; color: #1e3a8a; font-size: 12px; }
        .kosong { text-align: center; color: #64748b; padding: 24px 0; }
        .agenda { font-weight: bold; color: #1e3a8a; white-space: nowrap; }
        .tbl-wrap { overflow-x: auto; }
    </style>

    <div class="top-bar">
        <h1>Surat Saya</h1>
        <a href="/pengirim/surat/baru" class="btn">+ Kirim Surat</a>
    </div>

    @if (session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="panel">
        @if ($daftarSurat->isEmpty())
            <div class="kosong">Belum ada surat yang kamu kirim.</div>
        @else
            <div class="tbl-wrap">
                <table class="tabel">
                    <thead>
                        <tr>
                            <th>No. Agenda</th>
                            <th>Tanggal surat</th>
                            <th>Nomor surat</th>
                            <th>Perihal</th>
                            <th>Status</th>
                            <th>File</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($daftarSurat as $surat)
                            <tr>
                                <td class="agenda">{{ $surat->nomor_agenda ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($surat->tanggal_surat)->format('d/m/Y') }}</td>
                                <td>{{ $surat->nomor_surat ?? '-' }}</td>
                                <td>{{ $surat->perihal }}</td>
                                <td><span class="badge">{{ ucfirst($surat->status) }}</span></td>
                                <td><a href="{{ asset('storage/' . $surat->file_surat) }}" target="_blank">Lihat</a></td>
                                <td><a href="/pengirim/surat/{{ $surat->id }}" class="btn-sm">Detail</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection