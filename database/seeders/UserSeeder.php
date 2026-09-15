<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = env('ADMIN_EMAIL');
        $name = env('ADMIN_NAME', 'Pengguna');
        $password = env('ADMIN_PASSWORD');

        if (!$email || !$password) {
            throw new \RuntimeException('ADMIN_EMAIL dan ADMIN_PASSWORD wajib diisi di file .env sebelum menjalankan seeder.');
        }

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make($password),
            ]
        );
    }
}