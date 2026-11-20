@extends('layouts.app')

@section('title', 'Dashboard Pengirim')

@section('content')
    <style>
        .dash-head { display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 12px; margin-bottom: 20px; }
        .dash-head h1 { margin: 0; }
        .dash-head .sub { color: #64748b; font-size: 14px; margin-top: 4px; }
        .btn { display: inline-block; padding: 11px 20px; background: #1e3a8a; color: #fff; text-decoration: none; border-radius: 8px; font-size: 14px; font-weight: 600; }
        .btn:hover { background: #1e40af; }

        .cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(170px, 1fr)); gap: 16px; margin-bottom: 20px; }
        .card { background: #fff; border-radius: 12px; padding: 18px 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.06); border-left: 5px solid #1e3a8a; }
        .card .label { font-size: 13px; color: #64748b; }
        .card .angka { font-size: 30px; font-weight: bold; color: #1e293b; margin-top: 4px; }
        .card.c-dikirim { border-left-color: #94a3b8; }
        .card.c-diterima { border-left-color: #3b82f6; }
        .card.c-diteruskan { border-left-color: #f59e0b; }
        .card.c-didisposisi { border-left-color: #8b5cf6; }

        .grid-2 { display: grid; grid-template-columns: 2fr 1fr; gap: 16px; margin-bottom: 20px; }
        .grid-2b { display: grid; grid-template-columns: 3fr 2fr; gap: 16px; margin-bottom: 20px; }
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

        .badge { display: inline-block; padding: 3px 10px; border-radius: 999px; font-size: 12px; font-weight: 600; text-transform: capitalize; }
        .kosong { color: #94a3b8; font-size: 14px; padding: 12px 0; }

        .timeline { list-style: none; margin: 0; padding: 0; }
        .timeline li { position: relative; padding: 0 0 16px 22px; border-left: 2px solid #e2e8f0; margin-left: 6px; font-size: 14px; }
        .timeline li:last-child { padding-bottom: 0; border-left-color: transparent; }
        .timeline li::before { content: ''; position: absolute; left: -7px; top: 3px; width: 12px; height: 12px; border-radius: 50%; background: #1e3a8a; border: 2px solid #fff; box-shadow: 0 0 0 1px #cbd5e1; }
        .timeline .waktu { color: #94a3b8; font-size: 12px; }
        .timeline .ket { color: #64748b; font-size: 13px; }
        .link-semua { display: inline-block; margin-top: 12px; font-size: 13px; color: #1e3a8a; text-decoration: none; font-weight: 600; }
    </style>

    <div class="dash-head">
        <div>
            <h1>Dashboard Pengirim</h1>
            <div class="sub">Selamat datang, <strong>{{ auth()->user()->name }}</strong>. Pantau perjalanan surat Anda di sini.</div>
        </div>
        <a href="/pengirim/surat/baru" class="btn">+ Kirim Surat Baru</a>
    </div>

    {{-- Kartu ringkasan --}}
    <div class="cards">
        <div class="card">
            <div class="label">Total Surat Saya</div>
            <div class="angka">{{ $totalSurat }}</div>
        </div>
        <div class="card c-dikirim">
            <div class="label">Dikirim</div>
            <div class="angka">{{ $hitung['dikirim'] }}</div>
        </div>
        <div class="card c-diterima">
            <div class="label">Diterima Staff</div>
            <div class="angka">{{ $hitung['diterima'] }}</div>
        </div>
        <div class="card c-diteruskan">
            <div class="label">Diteruskan ke Pimpinan</div>
            <div class="angka">{{ $hitung['diteruskan'] }}</div>
        </div>
        <div class="card c-didisposisi">
            <div class="label">Didisposisi</div>
            <div class="angka">{{ $hitung['didisposisi'] }}</div>
        </div>
    </div>

    {{-- Grafik --}}
    <div class="grid-2">
        <div class="panel">
            <h3>Surat yang Saya Kirim (12 bulan terakhir)</h3>
            <div class="chart-box"><canvas id="grafikBulan"></canvas></div>
        </div>
        <div class="panel">
            <h3>Komposisi Status</h3>
            <div class="chart-box"><canvas id="grafikStatus"></canvas></div>
        </div>
    </div>

    <div class="grid-2b">
        {{-- Surat terbaru --}}
        <div class="panel">
            <h3>Surat Terbaru Saya</h3>
            @if ($suratTerbaru->isEmpty())
                <div class="kosong">Belum ada surat. Klik "Kirim Surat Baru" untuk memulai.</div>
            @else
                <div class="tbl-wrap">
                    <table class="tbl">
                        <thead>
                            <tr><th>No. Agenda</th><th>Perihal</th><th>Status</th><th></th></tr>
                        </thead>
                        <tbody>
                            @foreach ($suratTerbaru as $s)
                                <tr>
                                    <td class="agenda">{{ $s->nomor_agenda ?? '-' }}</td>
                                    <td>{{ \Illuminate\Support\Str::limit($s->perihal, 40) }}</td>
                                    <td><span class="badge">{{ $s->status }}</span></td>
                                    <td><a href="/pengirim/surat/{{ $s->id }}">Detail</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
            <a class="link-semua" href="/pengirim/surat">Lihat semua surat saya &rarr;</a>
        </div>

        {{-- Aktivitas --}}
        <div class="panel">
            <h3>Perkembangan Surat Saya</h3>
            @if ($aktivitas->isEmpty())
                <div class="kosong">Belum ada aktivitas.</div>
            @else
                <ul class="timeline">
                    @foreach ($aktivitas as $a)
                        <li>
                            <div>
                                <span class="badge">{{ $a->status }}</span>
                                <a href="/pengirim/surat/{{ $a->surat_id }}" style="color:#1e293b;text-decoration:none;">{{ \Illuminate\Support\Str::limit($a->perihal, 28) }}</a>
                            </div>
                            @if ($a->keterangan)
                                <div class="ket">{{ \Illuminate\Support\Str::limit($a->keterangan, 60) }}</div>
                            @endif
                            <div class="waktu">{{ $a->nomor_agenda }} &middot; {{ $a->created_at ? \Carbon\Carbon::parse($a->created_at)->diffForHumans() : '' }}</div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
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
                        label: 'Jumlah surat',
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