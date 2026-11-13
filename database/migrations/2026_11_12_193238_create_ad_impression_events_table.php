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
        Schema::create('ad_impression_events', function (Blueprint $table) {
            $table->id();
            $table->string('event_key', 100)->unique();

            $table->foreignId('campaign_id')
                ->constrained('ad_campaigns')
                ->restrictOnDelete();

            $table->foreignId('creative_id')
                ->constrained('ad_creatives')
                ->restrictOnDelete();

            $table->foreignId('placement_id')
                ->constrained('ad_placements')
                ->restrictOnDelete();

            $table->foreignId('category_id')
                ->nullable()
                ->constrained('categories')
                ->nullOnDelete();

            $table->date('date');

            $table->unsignedInteger('impressions')->default(1);
            $table->unsignedInteger('billable_impressions')->default(0);
            $table->unsignedBigInteger('billed_tokens')->default(0);

            $table->timestamps();

            $table->index(['campaign_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_impression_events');
    }
};
