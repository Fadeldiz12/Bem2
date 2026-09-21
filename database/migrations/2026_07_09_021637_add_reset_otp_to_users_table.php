<?php
// Path asli: database/migrations/2026_07_09_100000_add_reset_otp_to_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Kode OTP disimpan sudah dalam bentuk hash (Hash::make), bukan plaintext.
            $table->string('reset_otp')->nullable()->after('session_id');
            $table->timestamp('reset_otp_expires_at')->nullable()->after('reset_otp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['reset_otp', 'reset_otp_expires_at']);
        });
    }
};
