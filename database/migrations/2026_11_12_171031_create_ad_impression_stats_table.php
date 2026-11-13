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
        Schema::create('ad_impression_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')
                ->constrained('ad_campaigns')
                ->restrictOnDelete();

            $table->foreignId('creative_id')
                ->constrained('ad_creatives')
                ->restrictOnDelete();

            $table->foreignId('placement_id')
                ->constrained('ad_placements')
                ->restrictOnDelete();

            $table->foreignId('rate_id')
                ->nullable()
                ->constrained('ad_placement_rates')
                ->nullOnDelete();

            $table->foreignId('category_id')
                ->nullable()
                ->constrained('categories')
                ->nullOnDelete();

            $table->date('date');

            $table->unsignedBigInteger('impressions')->default(0);
            $table->unsignedBigInteger('billable_impressions')->default(0);
            $table->unsignedBigInteger('billed_tokens')->default(0);

            // Snapshot tarif yang dipakai pada agregasi ini.
            $table->decimal('rate_per_1000', 10, 4);

            $table->timestamps();

            $table->unique([
                'campaign_id',
                'creative_id',
                'placement_id',
                'category_id',
                'date',
            ], 'ad_impression_daily_unique');

            $table->index(['date', 'placement_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_impression_stats');
    }
};
