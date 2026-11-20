<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    private const DAFTAR_STATUS = ['dikirim', 'diterima', 'diteruskan', 'didisposisi'];

    // ===================== STAFF =====================
    public function staff()
    {
        $hitung = $this->hitungStatus();
        $totalSurat = Surat::count();

        $grafikBulan = $this->grafikBulan(
            Surat::where('created_at', '>=', now()->startOfMonth()->subMonths(11))->pluck('created_at')
        );

        // Surat yang perlu ditangani staff (status "dikirim")
        $perluDiterima = DB::table('surat')
            ->leftJoin('users', 'users.id', '=', 'surat.pengirim_id')
            ->where('surat.status', 'dikirim')
            ->orderByDesc('surat.created_at')
            ->limit(5)
            ->select('surat.id', 'surat.nomor_agenda', 'surat.nomor_surat', 'surat.perihal', 'surat.jenis', 'surat.status', 'surat.created_at', 'users.name as pengirim')
            ->get();

        // Surat terbaru (semua status)
        $suratTerbaru = DB::table('surat')
            ->leftJoin('users', 'users.id', '=', 'surat.pengirim_id')
            ->orderByDesc('surat.created_at')
            ->limit(5)
            ->select('surat.id', 'surat.nomor_agenda', 'surat.nomor_surat', 'surat.perihal', 'surat.jenis', 'surat.status', 'surat.created_at', 'users.name as pengirim')
            ->get();

        // Aktivitas terbaru dari riwayat status
        $aktivitas = DB::table('riwayat_status')
            ->join('surat', 'surat.id', '=', 'riwayat_status.surat_id')
            ->orderByDesc('riwayat_status.created_at')
            ->limit(6)
            ->select('riwayat_status.status', 'riwayat_status.keterangan', 'riwayat_status.created_at', 'surat.id as surat_id', 'surat.perihal')
            ->get();

        return view('staff.dashboard', compact(
            'totalSurat',
            'hitung',
            'grafikBulan',
            'perluDiterima',
            'suratTerbaru',
            'aktivitas'
        ));
    }

    // ===================== PIMPINAN =====================
    public function pimpinan()
    {
        $id = auth()->id();
        $hitung = $this->hitungStatus();
        $totalSurat = Surat::count();

        $totalDisposisi = DB::table('disposisi')->where('pimpinan_id', $id)->count();

        // Disposisi dengan batas waktu dalam 7 hari ke depan
        $batasDekat = DB::table('disposisi')
            ->join('surat', 'surat.id', '=', 'disposisi.surat_id')
            ->where('disposisi.pimpinan_id', $id)
            ->whereBetween('disposisi.batas_waktu', [today()->toDateString(), today()->addDays(7)->toDateString()])
            ->orderBy('disposisi.batas_waktu')
            ->select('disposisi.id', 'disposisi.isi_disposisi', 'disposisi.batas_waktu', 'surat.id as surat_id', 'surat.nomor_agenda', 'surat.perihal')
            ->get();
        $jumlahBatasDekat = $batasDekat->count();

        // Surat yang menunggu disposisi (status "diteruskan")
        $menunggu = DB::table('surat')
            ->leftJoin('users', 'users.id', '=', 'surat.pengirim_id')
            ->where('surat.status', 'diteruskan')
            ->orderByDesc('surat.created_at')
            ->limit(5)
            ->select('surat.id', 'surat.nomor_agenda', 'surat.perihal', 'surat.created_at', 'users.name as pengirim')
            ->get();

        // Disposisi terbaru yang pernah diberikan
        $disposisiTerbaru = DB::table('disposisi')
            ->join('surat', 'surat.id', '=', 'disposisi.surat_id')
            ->where('disposisi.pimpinan_id', $id)
            ->orderByDesc('disposisi.created_at')
            ->limit(5)
            ->select('disposisi.isi_disposisi', 'disposisi.tanggal_disposisi', 'disposisi.batas_waktu', 'surat.id as surat_id', 'surat.nomor_agenda', 'surat.perihal')
            ->get();

        $grafikBulan = $this->grafikBulan(
            DB::table('disposisi')
                ->where('pimpinan_id', $id)
                ->where('tanggal_disposisi', '>=', now()->startOfMonth()->subMonths(11)->toDateString())
                ->pluck('tanggal_disposisi')
        );

        return view('pimpinan.dashboard', compact(
            'totalSurat',
            'hitung',
            'totalDisposisi',
            'jumlahBatasDekat',
            'batasDekat',
            'menunggu',
            'disposisiTerbaru',
            'grafikBulan'
        ));
    }

    // ===================== PENGIRIM =====================
    public function pengirim()
    {
        $id = auth()->id();

        $hitung = $this->hitungStatus($id);
        $totalSurat = Surat::where('pengirim_id', $id)->count();

        $grafikBulan = $this->grafikBulan(
            Surat::where('pengirim_id', $id)
                ->where('created_at', '>=', now()->startOfMonth()->subMonths(11))
                ->pluck('created_at')
        );

        // Surat terbaru milik pengirim ini
        $suratTerbaru = DB::table('surat')
            ->where('pengirim_id', $id)
            ->orderByDesc('created_at')
            ->limit(5)
            ->select('id', 'nomor_agenda', 'nomor_surat', 'perihal', 'status', 'created_at')
            ->get();

        // Aktivitas terbaru pada surat milik pengirim ini
        $aktivitas = DB::table('riwayat_status')
            ->join('surat', 'surat.id', '=', 'riwayat_status.surat_id')
            ->where('surat.pengirim_id', $id)
            ->orderByDesc('riwayat_status.created_at')
            ->limit(6)
            ->select('riwayat_status.status', 'riwayat_status.keterangan', 'riwayat_status.created_at', 'surat.id as surat_id', 'surat.nomor_agenda', 'surat.perihal')
            ->get();

        return view('pengirim.dashboard', compact(
            'totalSurat',
            'hitung',
            'grafikBulan',
            'suratTerbaru',
            'aktivitas'
        ));
    }

    // ===================== BANTUAN =====================

    // Jumlah surat per status (selalu berisi 4 status, nol kalau kosong).
    // Kalau $pengirimId diisi, hanya menghitung surat milik pengirim itu.
    private function hitungStatus(?int $pengirimId = null): array
    {
        $query = Surat::select('status', DB::raw('count(*) as total'))->groupBy('status');

        if ($pengirimId !== null) {
            $query->where('pengirim_id', $pengirimId);
        }

        $perStatus = $query->pluck('total', 'status');

        $hitung = [];
        foreach (self::DAFTAR_STATUS as $s) {
            $hitung[$s] = (int) ($perStatus[$s] ?? 0);
        }

        return $hitung;
    }

    // Menghitung jumlah per bulan untuk 12 bulan terakhir dari daftar tanggal
    private function grafikBulan($daftarTanggal): array
    {
        $bulan = [];
        for ($i = 11; $i >= 0; $i--) {
            $d = now()->startOfMonth()->subMonths($i);
            $bulan[$d->format('Y-m')] = ['label' => $d->format('M y'), 'total' => 0];
        }

        foreach ($daftarTanggal as $t) {
            if (! $t) {
                continue;
            }
            $key = Carbon::parse($t)->format('Y-m');
            if (isset($bulan[$key])) {
                $bulan[$key]['total']++;
            }
        }

        return [
            'label' => array_column($bulan, 'label'),
            'data' => array_column($bulan, 'total'),
        ];
    }
}