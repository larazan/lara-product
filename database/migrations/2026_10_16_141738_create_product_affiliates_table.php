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
        Schema::create('product_affiliates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnDelete();

            $table->string('merchant'); // Shopee, Tokopedia, Lazada, dll.
            $table->string('store_name')->nullable();
            $table->text('url'); // Link affiliate

            $table->decimal('price', 15, 2)->nullable();
            $table->string('currency', 3)->default('IDR');

            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamp('last_checked_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['product_id', 'is_active']);
            $table->index(['merchant', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_affiliates');
    }
};
