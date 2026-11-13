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
        Schema::create('advertiser_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_id')
                ->constrained('advertiser_wallets')
                ->restrictOnDelete();

            $table->foreignId('user_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('campaign_id')
                ->nullable()
                ->constrained('ad_campaigns')
                ->nullOnDelete();

            $table->string('type', 30);
            // purchase, spend, refund, adjustment, expiration

            $table->bigInteger('token_amount');
            // Positif untuk kredit, negatif untuk debit.

            $table->unsignedBigInteger('balance_before');
            $table->unsignedBigInteger('balance_after');

            $table->string('reference_type')->nullable();
            $table->string('reference_id')->nullable();
            $table->string('idempotency_key')->nullable()->unique();

            $table->json('metadata')->nullable();
            $table->text('description')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index(['campaign_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advertiser_transactions');
    }
};
