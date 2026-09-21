<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Akun demo untuk modul LPJ/Proposal (role admin & user).
     * Akun super_admin utama sudah dibuat oleh DatabaseSeeder,
     * jadi di sini cukup tambahkan contoh admin & user biasa.
     */
    public function run(): void
    {
        // Admin (modul LPJ/Proposal — role 'admin', bukan super_admin)
        User::firstOrCreate(
            ['email' => 'admin.rab@bempolmed.ac.id'],
            [
                'name'          => 'Admin RAB',
                'password_hash' => Hash::make('password123'),
                'role'          => 'admin',
                'is_active'     => true,
                'last_login'    => Carbon::now(),
            ]
        );

        // User biasa (anggota yang mengajukan Proposal/LPJ sendiri)
        User::firstOrCreate(
            ['email' => 'user.rab@bempolmed.ac.id'],
            [
                'name'          => 'Anggota RAB',
                'password_hash' => Hash::make('password123'),
                'role'          => 'user',
                'is_active'     => true,
                'last_login'    => Carbon::now(),
            ]
        );

        echo "✅ Users modul LPJ/Proposal seeded successfully!\n";
        echo "Admin: admin.rab@bempolmed.ac.id / password123\n";
        echo "User : user.rab@bempolmed.ac.id / password123\n";
    }
}
