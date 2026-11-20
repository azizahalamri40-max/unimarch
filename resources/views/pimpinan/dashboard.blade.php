@extends('layouts.app')

@section('title', 'Dashboard Pimpinan')

@section('content')
    <style>
        .dash-head { display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 8px; margin-bottom: 20px; }
        .dash-head h1 { margin: 0; }
        .dash-head .sub { color: #64748b; font-size: 14px; margin-top: 4px; }
        .dash-head .tanggal { color: #64748b; font-size: 13px; }

        .notice { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; margin-bottom: 20px; padding: 14px 18px; background: #fef3c7; color: #92400e; border-radius: 12px; font-size: 14px; }
        .notice a { color: #92400e; font-weight: bold; text-decoration: none; }

        .cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(170px, 1fr)); gap: 16px; margin-bottom: 20px; }
        .card { background: #fff; border-radius: 12px; padding: 18px 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.06); border-left: 5px solid #1e3a8a; }
        .card .label { font-size: 13px; color: #64748b; }
        .card .angka { font-size: 30px; font-weight: bold; color: #1e293b; margin-top: 4px; }
        .card.c-diteruskan { border-left-color: #f59e0b; }
        .card.c-didisposisi { border-left-color: #8b5cf6; }
        .card.c-saya { border-left-color: #10b981; }
        .card.c-batas { border-left-color: #ef4444; }

        .grid-2 { display: grid; grid-template-columns: 2fr 1fr; gap: 16px; margin-bottom: 20px; }
        .grid-2b { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px; }
        @media (max-width: 900px) { .grid-2, .grid-2b { grid-template-columns: 1fr; } }

        .panel h3 { margin: 0 0 14px; font-size: 16px; color: #1e3a8a; }
        .chart-box { position: relative; height: 260px; }

        .tbl-wrap { overflow-x: auto; }
        table.tbl { width: 100%; border-collapse: collapse; font-size: 14px; }
        table.tbl th { text-align: left; color: #64748b; font-weight: 600; font-size: 12px; padding: 8px 10px; border-bottom: 1px solid #e2e8f0; }
        table.tbl td { padding: 10px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
        table.tbl a { color: #1e3a8a; text-decoration: none; font-weight: 600; }
        table.tbl a:hover { text-decoration: underline; }
        .agenda { font-weight: bold; color: #1e3a8a; white-space: nowrap; }
        .kosong { color: #94a3b8; font-size: 14px; padding: 12px 0; }
        .link-semua { display: inline-block; margin-top: 12px; font-size: 13px; color: #1e3a8a; text-decoration: none; font-weight: 600; }
        .tgl-merah { color: #b91c1c; font-weight: 600; white-space: nowrap; }
    </style>

    <div class="dash-head">
        <div>
            <h1>Dashboard Pimpinan</h1>
            <div class="sub">Selamat datang, <strong>{{ auth()->user()->name }}</strong>. Berikut ringkasan disposisi surat.</div>
        </div>
        <div class="tanggal">{{ now()->format('d M Y') }}</div>
    </div>

    @if ($hitung['diteruskan'] > 0)
        <div class="notice">
            <span>Ada <strong>{{ $hitung['diteruskan'] }}</strong> surat yang menunggu disposisi Anda.</span>
            <a href="/pimpinan/surat">Lihat surat &rarr;</a>
        </div>
    @endif

    {{-- Kartu ringkasan --}}
    <div class="cards">
        <div class="card">
            <div class="label">Total Surat</div>
            <div class="angka">{{ $totalSurat }}</div>
        </div>
        <div class="card c-diteruskan">
            <div class="label">Menunggu Disposisi</div>
            <div class="angka">{{ $hitung['diteruskan'] }}</div>
        </div>
        <div class="card c-didisposisi">
            <div class="label">Sudah Didisposisi</div>
            <div class="angka">{{ $hitung['didisposisi'] }}</div>
        </div>
        <div class="card c-saya">
            <div class="label">Disposisi Saya</div>
            <div class="angka">{{ $totalDisposisi }}</div>
        </div>
        <div class="card c-batas">
            <div class="label">Batas Waktu 7 Hari</div>
            <div class="angka">{{ $jumlahBatasDekat }}</div>
        </div>
    </div>

    {{-- Grafik --}}
    <div class="grid-2">
        <div class="panel">
            <h3>Disposisi per Bulan (12 bulan terakhir)</h3>
            <div class="chart-box"><canvas id="grafikBulan"></canvas></div>
        </div>
        <div class="panel">
            <h3>Komposisi Status Surat</h3>
            <div class="chart-box"><canvas id="grafikStatus"></canvas></div>
        </div>
    </div>

    {{-- Menunggu disposisi + batas waktu --}}
    <div class="grid-2b">
        <div class="panel">
            <h3>Menunggu Disposisi</h3>
            @if ($menunggu->isEmpty())
                <div class="kosong">Tidak ada surat yang menunggu. Semua sudah didisposisi. 🎉</div>
            @else
                <div class="tbl-wrap">
                    <table class="tbl">
                        <thead>
                            <tr><th>No. Agenda</th><th>Perihal</th><th>Pengirim</th><th></th></tr>
                        </thead>
                        <tbody>
                            @foreach ($menunggu as $s)
                                <tr>
                                    <td class="agenda">{{ $s->nomor_agenda ?? '-' }}</td>
                                    <td>{{ \Illuminate\Support\Str::limit($s->perihal, 40) }}</td>
                                    <td>{{ $s->pengirim ?? '-' }}</td>
                                    <td><a href="/pimpinan/surat/{{ $s->id }}">Disposisi</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
            <a class="link-semua" href="/pimpinan/surat">Lihat semua surat disposisi &rarr;</a>
        </div>

        <div class="panel">
            <h3>Batas Waktu Mendekati (7 hari)</h3>
            @if ($batasDekat->isEmpty())
                <div class="kosong">Tidak ada disposisi dengan batas waktu dalam 7 hari ke depan.</div>
            @else
                <div class="tbl-wrap">
                    <table class="tbl">
                        <thead>
                            <tr><th>Batas waktu</th><th>No. Agenda</th><th>Perihal</th></tr>
                        </thead>
                        <tbody>
                            @foreach ($batasDekat->take(5) as $b)
                                <tr>
                                    <td class="tgl-merah">{{ \Carbon\Carbon::parse($b->batas_waktu)->format('d/m/Y') }}</td>
                                    <td class="agenda">{{ $b->nomor_agenda ?? '-' }}</td>
                                    <td><a href="/pimpinan/surat/{{ $b->surat_id }}">{{ \Illuminate\Support\Str::limit($b->perihal, 30) }}</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- Disposisi terbaru --}}
    <div class="panel">
        <h3>Disposisi Terbaru</h3>
        @if ($disposisiTerbaru->isEmpty())
            <div class="kosong">Belum ada disposisi yang Anda berikan.</div>
        @else
            <div class="tbl-wrap">
                <table class="tbl">
                    <thead>
                        <tr><th>Tanggal</th><th>No. Agenda</th><th>Perihal</th><th>Isi disposisi</th><th>Batas waktu</th><th></th></tr>
                    </thead>
                    <tbody>
                        @foreach ($disposisiTerbaru as $d)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($d->tanggal_disposisi)->format('d/m/Y') }}</td>
                                <td class="agenda">{{ $d->nomor_agenda ?? '-' }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($d->perihal, 35) }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($d->isi_disposisi, 50) }}</td>
                                <td>{{ $d->batas_waktu ? \Carbon\Carbon::parse($d->batas_waktu)->format('d/m/Y') : '-' }}</td>
                                <td><a href="/pimpinan/surat/{{ $d->surat_id }}">Detail</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        (function () {
            if (typeof Chart === 'undefined') { return; }

            var bulan = @json($grafikBulan);
            var status = @json(array_values($hitung));

            new Chart(document.getElementById('grafikBulan'), {
                type: 'bar',
                data: {
                    labels: bulan.label,
                    datasets: [{
                        label: 'Jumlah disposisi',
                        data: bulan.data,
                        backgroundColor: '#1e3a8a',
                        borderRadius: 6
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
                }
            });

            new Chart(document.getElementById('grafikStatus'), {
                type: 'doughnut',
                data: {
                    labels: ['Dikirim', 'Diterima', 'Diteruskan', 'Didisposisi'],
                    datasets: [{
                        data: status,
                        backgroundColor: ['#94a3b8', '#3b82f6', '#f59e0b', '#8b5cf6'],
                        borderWidth: 0
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    cutout: '62%',
                    plugins: { legend: { position: 'bottom' } }
                }
            });
        })();
    </script>
@endsection