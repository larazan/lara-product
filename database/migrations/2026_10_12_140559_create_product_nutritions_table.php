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
        Schema::create('product_nutritions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            // Jika Anda ingin menghubungkannya ke product_variants, ganti foreignId di atas 
            // atau tambahkan variant_id opsional jika diperlukan:
            // $table->foreignId('product_variant_id')->nullable()->constrained('product_variants')->onDelete('cascade');

            $table->string('serving_size', 50); // Contoh: "Per 100g", "Per 250ml", "1 Saji (30g)"
            $table->decimal('calories', 8, 2)->nullable();
            $table->decimal('total_fat', 8, 2)->nullable();
            $table->decimal('saturated_fat', 8, 2)->nullable();
            $table->decimal('carbohydrates', 8, 2)->nullable();
            $table->decimal('sugars', 8, 2)->nullable();
            $table->decimal('proteins', 8, 2)->nullable();
            $table->decimal('sodium', 8, 2)->nullable();
            $table->json('additional_nutrients')->nullable(); // Vitamin C, Kalsium, dll
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_nutritions');
    }
};
