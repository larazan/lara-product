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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();

            // status subscription
            $table->enum('status', [
                'pending',
                'active',
                'expired',
                'canceled'
            ])->default('pending');

            // $table->string('plan'); // e.g. premium_12_months
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();

            // cancel info
            $table->timestamp('canceled_at')->nullable();

            // optional metadata
            $table->json('meta')->nullable();


            $table->timestamps();

            $table->index(['user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
