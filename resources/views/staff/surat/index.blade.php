@extends('layouts.app')

@section('title', 'Surat Masuk')

@section('content')
    <style>
        table.tabel { width: 100%; border-collapse: collapse; font-size: 14px; }
        table.tabel th, table.tabel td { padding: 10px 12px; text-align: left; border-bottom: 1px solid #e2e8f0; }
        table.tabel th { background: #f8fafc; color: #475569; }
        .badge { display: inline-block; padding: 3px 10px; border-radius: 999px; background: #dbeafe; color: #1e3a8a; font-size: 12px; }
        .kosong { text-align: center; color: #64748b; padding: 24px 0; }
        .btn-sm { display: inline-block; padding: 6px 12px; background: #1e3a8a; color: #fff; text-decoration: none; border-radius: 6px; font-size: 13px; }
        .btn-sm:hover { background: #1e40af; }
        .sub { color: #64748b; font-size: 12px; }
        .agenda { font-weight: bold; color: #1e3a8a; white-space: nowrap; }
        .tbl-wrap { overflow-x: auto; }
    </style>

    <h1>Surat Masuk</h1>

    <div class="panel">
        @if ($daftarSurat->isEmpty())
            <div class="kosong">Belum ada surat masuk.</div>
        @else
            <div class="tbl-wrap">
                <table class="tabel">
                    <thead>
                        <tr>
                            <th>No. Agenda</th>
                            <th>Tanggal surat</th>
                            <th>Pengirim</th>
                            <th>Perihal</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($daftarSurat as $surat)
                            <tr>
                                <td class="agenda">{{ $surat->nomor_agenda ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($surat->tanggal_surat)->format('d/m/Y') }}</td>
                                <td>
                                    {{ $surat->pengirim->name }}
                                    <div class="sub">{{ ucfirst($surat->pengirim->tipe_pengirim ?? '-') }}</div>
                                </td>
                                <td>{{ $surat->perihal }}</td>
                                <td><span class="badge">{{ ucfirst($surat->status) }}</span></td>
                                <td><a href="/staff/surat/{{ $surat->id }}" class="btn-sm">Detail</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection