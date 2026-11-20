@extends('layouts.app')

@section('title', 'Arsip Surat')

@section('content')
    @php
        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
        $daftarStatus = ['dikirim', 'diterima', 'diteruskan', 'didisposisi'];
    @endphp

    <style>
        .filter-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 14px; }
        .filter-grid .wide { grid-column: span 2; }
        @media (max-width: 700px) { .filter-grid .wide { grid-column: span 1; } }
        .filter-grid label { display: block; margin-bottom: 6px; font-size: 13px; color: #475569; }
        .filter-grid select, .filter-grid input[type=text] {
            width: 100%; padding: 9px 10px; border: 1px solid #cbd5e1; border-radius: 8px;
            font-size: 14px; background: #fff; font-family: inherit;
        }
        .filter-actions { margin-top: 16px; display: flex; gap: 10px; }
        .btn { display: inline-block; padding: 9px 18px; background: #1e3a8a; color: #fff; border: 0; border-radius: 8px; font-size: 14px; cursor: pointer; text-decoration: none; }
        .btn:hover { background: #1e40af; }
        .btn-light { background: #e2e8f0; color: #334155; }
        .btn-light:hover { background: #cbd5e1; }
        .btn-sm { display: inline-block; padding: 6px 12px; background: #1e3a8a; color: #fff; text-decoration: none; border-radius: 6px; font-size: 13px; }
        .btn-sm:hover { background: #1e40af; }
        .mt { margin-top: 20px; }
        .hasil-info { margin-bottom: 12px; font-size: 14px; color: #475569; }
        table.tabel { width: 100%; border-collapse: collapse; font-size: 14px; }
        table.tabel th, table.tabel td { padding: 10px 12px; text-align: left; border-bottom: 1px solid #e2e8f0; }
        table.tabel th { background: #f8fafc; color: #475569; }
        .badge { display: inline-block; padding: 3px 10px; border-radius: 999px; background: #dbeafe; color: #1e3a8a; font-size: 12px; }
        .kosong { text-align: center; color: #64748b; padding: 24px 0; }
        .sub { color: #64748b; font-size: 12px; }
        .pager { display: flex; justify-content: space-between; align-items: center; margin-top: 16px; font-size: 14px; color: #475569; }
        .pager a { color: #1e3a8a; text-decoration: none; }
        .pager .off { color: #cbd5e1; }
        .alert-error { margin-bottom: 16px; padding: 10px 14px; background: #fee2e2; color: #b91c1c; border-radius: 8px; font-size: 14px; }
        .agenda { font-weight: bold; color: #1e3a8a; white-space: nowrap; }
        .tbl-wrap { overflow-x: auto; }
    </style>

    <h1>Arsip Surat</h1>

    @if ($errors->any())
        <div class="alert-error">{{ $errors->first() }}</div>
    @endif

    <div class="panel">
        <form method="GET" action="{{ url($prefix . '/arsip') }}">
            <div class="filter-grid">
                <div>
                    <label for="tahun">Tahun</label>
                    <select id="tahun" name="tahun">
                        <option value="">Semua tahun</option>
                        @foreach ($daftarTahun as $t)
                            <option value="{{ $t }}" @selected(request('tahun') == $t)>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="bulan">Bulan</label>
                    <select id="bulan" name="bulan">
                        <option value="">Semua bulan</option>
                        @foreach ($namaBulan as $no => $nama)
                            <option value="{{ $no }}" @selected(request('bulan') == $no)>{{ $nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="tanggal">Tanggal</label>
                    <select id="tanggal" name="tanggal">
                        <option value="">Semua tanggal</option>
                        @for ($i = 1; $i <= 31; $i++)
                            <option value="{{ $i }}" @selected(request('tanggal') == $i)>{{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="">Semua status</option>
                        @foreach ($daftarStatus as $s)
                            <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="wide">
                    <label for="q">Kata kunci</label>
                    <input type="text" id="q" name="q" value="{{ request('q') }}" placeholder="No. agenda, nomor surat, perihal, atau nama pengirim">
                </div>
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn">Cari</button>
                <a href="{{ url($prefix . '/arsip') }}" class="btn btn-light">Reset</a>
            </div>
        </form>
    </div>

    <div class="panel mt">
        <div class="hasil-info">Ditemukan <strong>{{ $daftarSurat->total() }}</strong> surat.</div>

        @if ($daftarSurat->isEmpty())
            <div class="kosong">Tidak ada surat yang cocok dengan pencarian ini.</div>
        @else
            <div class="tbl-wrap">
                <table class="tabel">
                    <thead>
                        <tr>
                            <th>No. Agenda</th>
                            <th>Tanggal surat</th>
                            <th>Nomor surat</th>
                            <th>Perihal</th>
                            <th>Pengirim</th>
                            <th>Status</th>
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
                                <td>
                                    {{ $surat->pengirim->name }}
                                    <div class="sub">{{ ucfirst($surat->pengirim->tipe_pengirim ?? '-') }}</div>
                                </td>
                                <td><span class="badge">{{ ucfirst($surat->status) }}</span></td>
                                <td><a href="{{ url($prefix . '/surat/' . $surat->id) }}" class="btn-sm">Detail</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($daftarSurat->hasPages())
                <div class="pager">
                    @if ($daftarSurat->onFirstPage())
                        <span class="off">&laquo; Sebelumnya</span>
                    @else
                        <a href="{{ $daftarSurat->previousPageUrl() }}">&laquo; Sebelumnya</a>
                    @endif

                    <span>Halaman {{ $daftarSurat->currentPage() }} dari {{ $daftarSurat->lastPage() }}</span>

                    @if ($daftarSurat->hasMorePages())
                        <a href="{{ $daftarSurat->nextPageUrl() }}">Berikutnya &raquo;</a>
                    @else
                        <span class="off">Berikutnya &raquo;</span>
                    @endif
                </div>
            @endif
        @endif
    </div>
@endsection