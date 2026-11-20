<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah kolom (boleh kosong dulu supaya surat lama tidak error)
        Schema::table('surat', function (Blueprint $table) {
            $table->string('nomor_agenda', 30)->nullable()->after('id');
        });

        // 2. Beri nomor ke surat yang sudah ada, urut per tahun
        $counter = [];
        DB::table('surat')->orderBy('id')->get(['id', 'created_at'])->each(function ($s) use (&$counter) {
            $tahun = $s->created_at ? Carbon::parse($s->created_at)->format('Y') : now()->format('Y');
            $counter[$tahun] = ($counter[$tahun] ?? 0) + 1;

            DB::table('surat')->where('id', $s->id)->update([
                'nomor_agenda' => sprintf('AGD-%s-%04d', $tahun, $counter[$tahun]),
            ]);
        });

        // 3. Pastikan tidak ada nomor agenda yang kembar
        Schema::table('surat', function (Blueprint $table) {
            $table->unique('nomor_agenda');
        });
    }

    public function down(): void
    {
        Schema::table('surat', function (Blueprint $table) {
            $table->dropUnique(['nomor_agenda']);
            $table->dropColumn('nomor_agenda');
        });
    }
};