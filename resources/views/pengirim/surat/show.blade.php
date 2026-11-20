@extends('layouts.app')

@section('title', 'Detail Surat')

@section('content')
    <style>
        .back { display: inline-block; margin-bottom: 12px; color: #1e3a8a; text-decoration: none; font-size: 14px; }
        .panel h2 { margin: 0 0 14px; font-size: 17px; color: #1e3a8a; }
        .info { width: 100%; font-size: 14px; border-collapse: collapse; }
        .info td { padding: 6px 0; vertical-align: top; }
        .info td:first-child { width: 140px; color: #64748b; }
        .badge { display: inline-block; padding: 3px 10px; border-radius: 999px; background: #dbeafe; color: #1e3a8a; font-size: 12px; }
        table.tabel { width: 100%; border-collapse: collapse; font-size: 14px; }
        table.tabel th, table.tabel td { padding: 8px 10px; text-align: left; border-bottom: 1px solid #e2e8f0; vertical-align: top; }
        table.tabel th { background: #f8fafc; color: #475569; }
        .mt { margin-top: 20px; }
        .agenda-box { display: inline-block; padding: 6px 14px; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; color: #1e3a8a; font-weight: bold; font-size: 16px; letter-spacing: 0.5px; }
        .tbl-wrap { overflow-x: auto; }
    </style>

    <a href="/pengirim/surat" class="back">&larr; Kembali ke Surat Saya</a>
    <h1>Detail Surat</h1>

    <div class="panel">
        <h2>Informasi surat</h2>
        <table class="info">
            <tr><td>No. Agenda</td><td><span class="agenda-box">{{ $surat->nomor_agenda ?? '-' }}</span></td></tr>
            <tr><td>Perihal</td><td>{{ $surat->perihal }}</td></tr>
            <tr><td>Nomor surat</td><td>{{ $surat->nomor_surat ?? '-' }}</td></tr>
            <tr><td>Tanggal surat</td><td>{{ \Carbon\Carbon::parse($surat->tanggal_surat)->format('d/m/Y') }}</td></tr>
            <tr><td>Status saat ini</td><td><span class="badge">{{ ucfirst($surat->status) }}</span></td></tr>
            <tr><td>File surat</td><td><a href="{{ asset('storage/' . $surat->file_surat) }}" target="_blank">Buka file</a></td></tr>
        </table>
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