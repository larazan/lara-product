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
        Schema::create('ad_click_stats', function (Blueprint $table) {
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

            $table->date('date');
            $table->unsignedBigInteger('clicks')->default(0);
            $table->unsignedBigInteger('unique_clicks')->default(0);

            $table->timestamps();

            $table->unique([
                'campaign_id',
                'creative_id',
                'placement_id',
                'date',
            ], 'ad_click_daily_unique');

            $table->index(['date', 'placement_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_click_stats');
    }
};
