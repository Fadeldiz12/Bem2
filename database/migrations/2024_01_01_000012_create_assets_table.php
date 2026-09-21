<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('uploaded_by')->constrained('users')->cascadeOnDelete();
            $table->string('title', 150)->nullable();
            $table->enum('category', ['logo_kabinet','logo_kementerian','foto_pengurus','banner','dokumentasi','lainnya'])->default('lainnya');
            $table->string('file_path', 255);
            $table->string('file_type', 20)->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('assets'); }
};
