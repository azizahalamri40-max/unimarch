<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use Illuminate\Http\Request;

class ArsipController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'tahun' => ['nullable', 'integer', 'min:2000', 'max:2100'],
            'bulan' => ['nullable', 'integer', 'between:1,12'],
            'tanggal' => ['nullable', 'integer', 'between:1,31'],
            'status' => ['nullable', 'in:dikirim,diterima,diteruskan,didisposisi'],
            'q' => ['nullable', 'string', 'max:100'],
        ]);

        $query = Surat::with('pengirim')
            ->orderByDesc('tanggal_surat')
            ->orderByDesc('id');

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_surat', $request->tahun);
        }

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_surat', $request->bulan);
        }

        if ($request->filled('tanggal')) {
            $query->whereDay('tanggal_surat', $request->tanggal);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $kata = $request->q;

            $query->where(function ($w) use ($kata) {
                $w->where('perihal', 'like', "%{$kata}%")
                    ->orWhere('nomor_surat', 'like', "%{$kata}%")
                    ->orWhere('nomor_agenda', 'like', "%{$kata}%")
                    ->orWhereHas('pengirim', fn ($p) => $p->where('name', 'like', "%{$kata}%"));
            });
        }

        $daftarSurat = $query->paginate(15)->withQueryString();

        // Pilihan tahun diambil dari surat yang ada di database
        $daftarTahun = Surat::selectRaw('YEAR(tanggal_surat) as tahun')
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun');

        $prefix = auth()->user()->role === 'pimpinan' ? 'pimpinan' : 'staff';

        return view('arsip.index', compact('daftarSurat', 'daftarTahun', 'prefix'));
    }
}