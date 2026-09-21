<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('management_year_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ministry_id')->constrained()->cascadeOnDelete();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->string('full_name', 100);
            $table->string('nim', 20)->nullable();
            $table->string('position', 100);
            $table->enum('role', ['menteri', 'koordinator', 'staff'])->default('staff');
            $table->boolean('is_head')->default(false);
            $table->string('photo_path', 255)->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('members'); }
};
