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
        Schema::create('labels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('label_group_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->json('name');
            $table->string('slug')->unique();
            $table->json('description')->nullable();

            $table->string('icon')->nullable();

            // marketing, certification, regulatory, safety
            $table->enum('label_type', [
                'marketing',
                'certification',
                'regulatory',
                'safety',
            ])->default('safety');

            // info, low, medium, high, critical
            $table->enum('severity', [
                'info',
                'low',
                'medium',
                'high',
                'critical',
            ])->default('info');

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index(['label_group_id', 'is_active']);
            $table->index(['label_type', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('labels');
    }
};