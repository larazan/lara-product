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
        Schema::create('attribute_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_attribute_id')
                ->unique()
                ->constrained('category_attributes')
                ->cascadeOnDelete();

            $table->decimal('weight', 8, 2)->default(1);

            $table->boolean('higher_is_better')->default(true);

            // Contoh: range, threshold, benchmark, manual
            $table->string('scoring_method')->default('range');

            // Batas atau parameter normalisasi
            $table->json('scoring_config')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attribute_scores');
    }
};
