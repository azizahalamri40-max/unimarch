<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Password awal diambil dari .env (SEEDER_PASSWORD).
        // Kalau tidak diatur, dipakai password123 (hanya untuk lokal).
        $password = env('SEEDER_PASSWORD', 'password123');

        $akun = [
            [
                'name' => 'Staff Perpustakaan',
                'email' => 'staff@unimarch.test',
                'role' => 'staff',
            ],
            [
                'name' => 'Pimpinan Perpustakaan',
                'email' => 'pimpinan@unimarch.test',
                'role' => 'pimpinan',
            ],
            [
                'name' => 'Budi Mahasiswa',
                'email' => 'pengirim@unimarch.test',
                'role' => 'pengirim',
                'tipe_pengirim' => 'mahasiswa',
            ],
        ];

        foreach ($akun as $data) {
            User::firstOrCreate(
                ['email' => $data['email']],
                $data + ['password' => $password]
            );
        }
    }
}