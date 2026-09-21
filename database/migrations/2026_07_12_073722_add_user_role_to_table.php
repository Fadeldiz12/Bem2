<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel `users` sudah berisi data asli (bukan instalasi baru), jadi
 * perubahan skema dilakukan lewat migration baru ini — BUKAN dengan
 * mengedit 2024_01_01_000001_create_users_table.php yang sudah pernah
 * dijalankan (mengedit migration lama tidak akan dieksekusi ulang oleh
 * Laravel, jadi tidak akan berpengaruh ke database yang sudah ada).
 */
return new class extends Migration
{
    public function up(): void
    {
        // Perluas enum role: tambah 'user' untuk anggota modul LPJ/Proposal
        // (sebelumnya cuma 'super_admin' & 'admin').
        // Pakai raw SQL, bukan Schema::table()->enum()->change(), karena yang
        // terakhir butuh paket doctrine/dbal dan kurang reliable untuk ENUM.
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin', 'admin', 'user') NOT NULL DEFAULT 'user'");

        // Kolom remember_token dibutuhkan Authenticatable contract Laravel
        // (fitur "remember me"), belum ada di tabel users sebelumnya.
        if (!Schema::hasColumn('users', 'remember_token')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('remember_token', 100)->nullable()->after('session_id');
            });
        }
    }

    public function down(): void
    {
        // Catatan: rollback ini akan GAGAL kalau masih ada baris dengan
        // role = 'user' — MySQL menolak MODIFY COLUMN kalau ada data yang
        // tidak muat di enum yang lebih sempit. Ubah/hapus dulu baris itu
        // manual sebelum rollback kalau memang perlu.
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin', 'admin') NOT NULL DEFAULT 'admin'");

        if (Schema::hasColumn('users', 'remember_token')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('remember_token');
            });
        }
    }
};