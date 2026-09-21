<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kegiatan;
use App\Models\User;
use Carbon\Carbon;

class KegiatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ambil data Admin yang sudah di-seed sebelumnya
        $admin = User::where('role', 'admin')->first();

        // 2. Buat data kegiatan jika Admin ditemukan
        if ($admin) {
            $dataKegiatan = [
                [
                    'user_id'             => $admin->id, // Mengaitkan kegiatan dengan Admin
                    'Nama_Kegiatan'        => 'Rapat Evaluasi BEM',
                    'Tanggal_Pelaksanaan'  => Carbon::now()->addDays(2),
                    'Jenis_RAB'            => 'Proposal',
                ],
                [
                    'user_id'             => $admin->id,
                    'Nama_Kegiatan'        => 'Seminar Teknologi 2026',
                    'Tanggal_Pelaksanaan'  => Carbon::now()->addDays(14),
                    'Jenis_RAB'            => 'LPJ',
                ],
            ];

            // 3. Masukkan ke database
            Kegiatan::insert($dataKegiatan);
        } else {
            $this->command->info('User Admin belum ada! Silakan jalankan UserSeeder terlebih dahulu.');
        }
    }
}
