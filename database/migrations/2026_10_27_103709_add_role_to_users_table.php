<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['pengirim', 'staff', 'pimpinan'])->default('pengirim')->after('password');
            $table->enum('tipe_pengirim', ['mahasiswa', 'instansi'])->nullable()->after('role');
            $table->string('no_telp', 20)->nullable()->after('tipe_pengirim');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'tipe_pengirim', 'no_telp']);
        });
    }
};