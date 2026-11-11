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
        Schema::create('product_labels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('label_id')
                ->constrained()
                ->cascadeOnDelete();

            // Optional value, e.g. 5%, 18+, GHS05
            $table->text('value')->nullable();

            $table->text('notes')->nullable();

            // manufacturer, official_document, admin, external_source
            $table->string('source')->nullable();

            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();

            $table->timestamps();

            $table->unique(['product_id', 'label_id']);
            $table->index(['label_id', 'is_verified']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_labels');
    }
};