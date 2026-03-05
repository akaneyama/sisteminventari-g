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
        // Akun Admin
        User::create([
            'nama_lengkap' => 'Admin Inventaris',
            'username'     => 'admin',
            'email'        => 'admin@sekolah.com',
            'password'     => 'password123',
            'role'         => 'Admin',
        ]);

        // Akun Kepala Sekolah
        User::create([
            'nama_lengkap' => 'Kepala Sekolah',
            'username'     => 'kepsek',
            'email'        => 'kepsek@sekolah.com',
            'password'     => 'password123',
            'role'         => 'Kepsek',
        ]);
    }
}