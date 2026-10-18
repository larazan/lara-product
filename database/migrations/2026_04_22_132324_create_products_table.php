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
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // User yang menambahkan produk (opsional)
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Klasifikasi produk
            $table->foreignId('category_id')
                ->constrained('categories')
                ->restrictOnDelete();

            $table->foreignId('brand_id')
                ->constrained('brands')
                ->restrictOnDelete();

            // Opsional jika perusahaan atau negara belum diketahui
            $table->foreignId('company_id')
                ->nullable()
                ->constrained('companies')
                ->nullOnDelete();

            $table->foreignId('country_id')
                ->nullable()
                ->constrained('countries')
                ->nullOnDelete();

            // Identitas produk
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('model_number')->nullable();

            // Informasi produk
            $table->text('description')->nullable();
            $table->year('year')->nullable();

            // Metadata SEO
            $table->string('meta_title')->nullable();
            $table->string('meta_description', 320)->nullable();

            // Metadata tambahan yang tidak menjadi atribut scoring
            $table->json('metadata')->nullable();

            // Status produk
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamp('published_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Index untuk katalog dan filter
            $table->index(['category_id', 'is_active']);
            $table->index(['brand_id', 'is_active']);
            $table->index(['company_id', 'is_active']);
            $table->index(['country_id', 'is_active']);
            $table->index(['is_featured', 'is_active']);
            $table->index('published_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
