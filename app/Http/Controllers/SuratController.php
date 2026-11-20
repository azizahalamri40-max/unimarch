<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuratController extends Controller
{
    // Daftar surat milik pengirim yang sedang login
    public function index()
    {
        $daftarSurat = auth()->user()->suratDikirim()->latest()->get();

        return view('pengirim.surat.index', compact('daftarSurat'));
    }

    // Menampilkan form upload
    public function create()
    {
        return view('pengirim.surat.create');
    }

    // Menyimpan surat dan file-nya
    public function store(Request $request)
    {
        $data = $request->validate([
            'nomor_surat' => ['nullable', 'string', 'max:255'],
            'perihal' => ['required', 'string', 'max:255'],
            'tanggal_surat' => ['required', 'date'],
            'file_surat' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ]);

        $path = $request->file('file_surat')->store('surat', 'public');

        // Simpan surat, nomor agenda, dan riwayat dalam satu transaksi
        $surat = DB::transaction(function () use ($data, $path) {
            $surat = Surat::create([
                'nomor_surat' => $data['nomor_surat'] ?? null,
                'perihal' => $data['perihal'],
                'jenis' => 'masuk',
                'tanggal_surat' => $data['tanggal_surat'],
                'file_surat' => $path,
                'pengirim_id' => auth()->id(),
                'status' => 'dikirim',
            ]);

            // Nomor agenda diisi terpisah, supaya aman walau ada aturan $fillable di model
            $surat->forceFill(['nomor_agenda' => $this->buatNomorAgenda()])->save();

            $surat->riwayatStatus()->create([
                'status' => 'dikirim',
                'keterangan' => 'Surat dikirim oleh pengirim.',
            ]);

            return $surat;
        });

        return redirect('/pengirim/surat')
            ->with('success', 'Surat berhasil dikirim. Nomor agenda: ' . $surat->nomor_agenda);
    }

    // Detail satu surat: status dan riwayat perjalanannya
    public function show(Surat $surat)
    {
        // Pengirim hanya boleh melihat surat miliknya sendiri
        abort_if($surat->pengirim_id !== auth()->id(), 403);

        $surat->load([
            'riwayatStatus' => fn ($q) => $q->latest(),
        ]);

        return view('pengirim.surat.show', compact('surat'));
    }

    // Membuat nomor agenda berikutnya, contoh: AGD-2026-0001 (urutan mulai lagi tiap tahun)
    private function buatNomorAgenda(): string
    {
        $awalan = 'AGD-' . now()->format('Y') . '-';

        $terakhir = Surat::where('nomor_agenda', 'like', $awalan . '%')
            ->lockForUpdate()
            ->orderByDesc('nomor_agenda')
            ->value('nomor_agenda');

        $urutan = $terakhir ? ((int) substr($terakhir, -4)) + 1 : 1;

        return $awalan . str_pad((string) $urutan, 4, '0', STR_PAD_LEFT);
    }
}