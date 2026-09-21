<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('management_years', function (Blueprint $table) {
            $table->text('filosofi_logo')->nullable()->after('misi');
            $table->text('makna_warna')->nullable()->after('filosofi_logo');
        });
    }
    public function down(): void
    {
        Schema::table('management_years', function (Blueprint $table) {
            $table->dropColumn(['filosofi_logo', 'makna_warna']);
        });
    }
};