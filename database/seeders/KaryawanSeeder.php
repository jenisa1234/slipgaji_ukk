<?php

namespace Database\Seeders;

use App\Models\Karyawan;
use Illuminate\Database\Seeder;

class KaryawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Karyawan::create([
            'nik'          => '3205011203000001',
            'nama'         => 'Budi Santoso',
            'jabatan'      => 'Junior Web Programmer',
            'no_whatsapp'  => '6281234567890',
            'email'        => 'budi@gmail.com',
            'gaji_pokok'   => 4500000,
            'lembur'       => 500000,
            'pinjaman'     => 200000,
        ]);

        Karyawan::create([
            'nik'          => '3205011203000002',
            'nama'         => 'Siti Aminah',
            'jabatan'      => 'UI/UX Designer',
            'no_whatsapp'  => '6289876543210',
            'email'        => 'siti@gmail.com',
            'gaji_pokok'   => 4200000,
            'lembur'       => 300000,
            'pinjaman'     => 0,
        ]);
    }
}