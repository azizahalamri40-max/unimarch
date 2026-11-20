<?php

namespace App\Http\Controllers;

use App\Models\Disposisi;
use App\Models\Surat;
use Illuminate\Http\Request;

class PimpinanSuratController extends Controller
{
    // Surat yang diteruskan staff, atau yang sudah pernah didisposisi
    public function index()
    {
        $daftarSurat = Surat::with('pengirim')
            ->where('status', 'diteruskan')
            ->orWhereHas('disposisi')
            ->latest()
            ->get();

        return view('pimpinan.surat.index', compact('daftarSurat'));
    }

    // Detail surat beserta disposisi dan riwayatnya
    public function show(Surat $surat)
    {
        $surat->load([
            'pengirim',
            'riwayatStatus' => fn ($q) => $q->latest(),
            'disposisi.pimpinan',
        ]);

        return view('pimpinan.surat.show', compact('surat'));
    }

    // Menyimpan disposisi, mengubah status surat, dan mencatat riwayat
    public function storeDisposisi(Request $request, Surat $surat)
    {
        // Hanya surat yang sudah diteruskan staff yang boleh didisposisi
        if (! in_array($surat->status, ['diteruskan', 'didisposisi'])) {
            return back()->withErrors([
                'isi_disposisi' => 'Surat ini belum diteruskan staff atau sudah ditangani, jadi belum bisa didisposisi.',
            ]);
        }

        $data = $request->validate([
            'isi_disposisi' => ['required', 'string', 'max:1000'],
            'batas_waktu' => ['nullable', 'date', 'after_or_equal:today'],
        ]);

        Disposisi::create([
            'surat_id' => $surat->id,
            'pimpinan_id' => auth()->id(),
            'isi_disposisi' => $data['isi_disposisi'],
            'tanggal_disposisi' => now()->toDateString(),
            'batas_waktu' => $data['batas_waktu'] ?? null,
        ]);

        $surat->update(['status' => 'didisposisi']);

        $surat->riwayatStatus()->create([
            'status' => 'didisposisi',
            'keterangan' => 'Disposisi diberikan oleh ' . auth()->user()->name . '.',
        ]);

        return redirect('/pimpinan/surat/' . $surat->id)->with('success', 'Disposisi berhasil disimpan.');
    }
}