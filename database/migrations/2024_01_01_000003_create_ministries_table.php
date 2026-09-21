<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ministries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('management_year_id')->constrained()->cascadeOnDelete();
            $table->string('name', 100);
            $table->string('alias', 50)->nullable();
            $table->string('logo_path', 255)->nullable();
            $table->text('description')->nullable();
            $table->text('tugas_pokok')->nullable();
            $table->string('whatsapp_number', 20)->nullable();
            $table->string('whatsapp_label', 100)->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('ministries'); }
};
