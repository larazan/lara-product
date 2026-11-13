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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained()->cascadeOnDelete();

            // informasi order
            $table->string('invoice_number')->unique();
            $table->bigInteger('amount'); // simpan dalam rupiah (tanpa desimal)
            $table->enum('payment_method', [
                'midtrans',
                'manual'
            ])->default('midtrans');

            // midtrans
            $table->string('midtrans_order_id')->nullable();
            $table->string('snap_token')->nullable();
            $table->string('payment_type')->nullable(); // bank_transfer, gopay, dll
            $table->string('proof_of_payment')->nullable();

            // status pembayaran
            $table->enum('status', [
                'pending',
                'paid',
                'failed',
                'expired'
            ])->default('pending');

            // optional tambahan
            $table->timestamp('paid_at')->nullable();
            $table->json('payload')->nullable(); // simpan response midtrans (opsional)
            $table->timestamps();

            $table->foreignId('verified_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('verified_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
