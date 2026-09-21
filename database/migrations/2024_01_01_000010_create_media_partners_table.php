<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('media_partners', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['internal', 'external']);
            $table->string('title', 100);
            $table->text('procedures')->nullable();
            $table->string('gform_link', 255)->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('media_partners'); }
};
