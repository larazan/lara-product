<?php

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
        Schema::create('product_safety_instructions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');

            // Tipe informasi: direction, warning, precaution, storage, first_aid
            $table->enum('instruction_type', ['direction', 'warning', 'precaution', 'storage', 'first_aid']);

            $table->unsignedTinyInteger('step_order')->default(1); // Urutan prioritas/langkah
            $table->string('title', 150)->nullable();             // Judul pendek
            $table->text('content');                              // Isi teks peringatan / instruksi
            $table->string('icon_code', 50)->nullable();          // Nama ikon untuk frontend UI
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_safety_instructions');
    }
};
