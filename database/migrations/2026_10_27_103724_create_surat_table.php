<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat')->nullable();
            $table->string('perihal');
            $table->enum('jenis', ['masuk', 'keluar']);
            $table->date('tanggal_surat');
            $table->string('file_surat');
            $table->foreignId('pengirim_id')->constrained('users');
            $table->foreignId('penerima_id')->nullable()->constrained('users');
            $table->string('status')->default('diterima');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat');
    }
};