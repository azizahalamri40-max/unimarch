<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use Illuminate\Http\Request;

class StaffSuratController extends Controller
{
    // Semua surat masuk
    public function index()
    {
        $daftarSurat = Surat::with('pengirim')->latest()->get();

        return view('staff.surat.index', compact('daftarSurat'));
    }

    // Detail satu surat beserta riwayatnya
    public function show(Surat $surat)
    {
        $surat->load([
            'pengirim',
            'riwayatStatus' => fn ($q) => $q->latest(),
            'disposisi.pimpinan',
        ]);

        return view('staff.surat.show', compact('surat'));
    }

    // Ubah status surat dan catat ke riwayat
    public function updateStatus(Request $request, Surat $surat)
    {
        // Surat yang sudah didisposisi pimpinan dikunci
        if ($surat->status === 'didisposisi') {
            return back()->withErrors([
                'status' => 'Surat sudah didisposisi pimpinan, jadi statusnya tidak bisa diubah lagi.',
            ]);
        }

        // Surat yang menunggu pimpinan tidak boleh diubah staff
        if ($surat->status === 'diteruskan') {
            return back()->withErrors([
                'status' => 'Surat sedang menunggu disposisi pimpinan, jadi statusnya belum bisa diubah.',
            ]);
        }

        $data = $request->validate([
            'status' => ['required', 'in:diterima,diteruskan'],
            'keterangan' => ['nullable', 'string', 'max:500'],
        ]);

        $surat->update([
            'status' => $data['status'],
            'penerima_id' => auth()->id(),
        ]);

        $surat->riwayatStatus()->create([
            'status' => $data['status'],
            'keterangan' => $data['keterangan'] ?? null,
        ]);

        return redirect('/staff/surat/' . $surat->id)->with('success', 'Status surat berhasil diperbarui.');
    }
}