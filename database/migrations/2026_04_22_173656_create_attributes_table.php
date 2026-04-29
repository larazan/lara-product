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
        Schema::create('attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')
                ->nullable()
                ->constrained('attribute_groups')
                ->nullOnDelete();
            $table->string('name');      // "weight"
            $table->string('label');     // "Weight"
            $table->string('unit')->nullable(); // "g", "mm", etc.
            $table->enum('type', ['text', 'number', 'boolean', 'select'])->default('text');
            $table->boolean('is_filterable')->default(false);
            $table->boolean('is_comparable')->default(true);

            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attributes');
    }
};
