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
            // $table->foreignId('group_id')
            //     ->nullable()
            //     ->constrained('attribute_groups')
            //     ->nullOnDelete();
            // $table->json('name');
            // $table->json('label');
            // $table->longText('icon_svg')->nullable();
            // $table->text('description')->nullable();
            // $table->string('unit')->nullable(); // "g", "mm", etc.
            // $table->enum('type', ['text', 'number', 'boolean', 'select'])->default('text');
            // $table->boolean('is_filterable')->default(false);
            // $table->boolean('is_comparable')->default(true);

            // $table->unsignedInteger('sort_order')->default(0);
            // $table->timestamps();

            // $table->unique('name');

            // 
            // Relasi ke grup atribut, boleh kosong jika atribut tidak masuk grup
            $table->foreignId('group_id')
                ->nullable()
                ->constrained('attribute_groups')
                ->nullOnDelete();

            // Identitas atribut yang stabil dan unik, misalnya weight atau screen_size
            $table->string('slug')->unique();

            // Mendukung terjemahan Indonesia dan Inggris
            $table->json('name');
            $table->json('label');

            // Ikon SVG opsional
            $table->longText('icon_svg')->nullable();

            // Penjelasan atribut untuk halaman produk atau tooltip
            $table->text('description')->nullable();

            // Satuan, misalnya g, mm, inch, mAh, GHz
            $table->string('unit', 30)->nullable();

            // Tipe data nilai atribut
            $table->enum('type', [
                'text',
                'number',
                'boolean',
                'select',
            ])->default('text');

            // Pengaturan tampilan dan pencarian
            $table->boolean('is_filterable')->default(false);
            $table->boolean('is_comparable')->default(true);

            // Pengaturan urutan atribut
            $table->unsignedInteger('sort_order')->default(0);

            // Status atribut
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            // Index untuk query berdasarkan grup dan status
            $table->index(['group_id', 'is_active']);
            $table->index(['is_filterable', 'is_active']);
            $table->index(['is_comparable', 'is_active']);
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
