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
        Schema::create('ad_campaign_placements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')
                ->constrained('ad_campaigns')
                ->cascadeOnDelete();

            $table->foreignId('placement_id')
                ->constrained('ad_placements')
                ->restrictOnDelete();

            $table->foreignId('category_id')
                ->nullable()
                ->constrained('categories')
                ->nullOnDelete();

            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(
                ['campaign_id', 'placement_id', 'category_id'],
                'campaign_placement_category_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_campaign_placements');
    }
};
