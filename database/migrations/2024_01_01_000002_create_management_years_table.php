<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('management_years', function (Blueprint $table) {
            $table->id();
            $table->string('year_label', 20);
            $table->string('cabinet_name', 100);
            $table->string('tagline', 150)->nullable();
            $table->string('logo_path', 255)->nullable();
            $table->text('visi')->nullable();
            $table->text('misi')->nullable();
            $table->string('presma_name', 100)->nullable();
            $table->string('presma_photo', 255)->nullable();
            $table->string('wapresma_name', 100)->nullable();
            $table->string('wapresma_photo', 255)->nullable();
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->boolean('is_active')->default(false);
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('management_years'); }
};
