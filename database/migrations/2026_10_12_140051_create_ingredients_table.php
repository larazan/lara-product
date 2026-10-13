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
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150)->unique();    // Contoh: "Niacinamide", "Glycerin", "Sodium Chloride"
            $table->string('inci_name', 150)->nullable(); // Nama resmi INCI untuk kosmetik
            $table->unsignedTinyInteger('safety_rating')->nullable(); // Skala 1-5 tingkat keamanan bahan
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredients');
    }
};
