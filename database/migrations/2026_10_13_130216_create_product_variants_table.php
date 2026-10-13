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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnDelete();

            $table->foreignId('country_id')
                ->nullable()
                ->constrained('countries')
                ->nullOnDelete();

            $table->uuid('uuid')->unique();

            // Contoh: Global, Indonesia, 12GB/512GB, Hybrid Premium
            $table->string('name');
            $table->string('slug');

            // SKU atau kode model dari produsen
            $table->string('sku')->nullable();

            // Harga opsional; harga regional bisa dipisahkan nantinya
            $table->decimal('price', 15, 2)->nullable();
            $table->char('currency', 3)->nullable();

            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);

            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();

            $table->unique(['product_id', 'slug']);
            $table->index(['product_id', 'is_active']);
            $table->index(['country_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
