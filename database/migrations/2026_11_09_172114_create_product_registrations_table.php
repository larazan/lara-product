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
        Schema::create('product_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('regulatory_authority_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('registration_number');

            // pending, approved, rejected, suspended, expired, revoked
            $table->enum('registration_status', [
                'pending',
                'approved',
                'rejected',
                'suspended',
                'expired',
                'revoked',
            ])->default('pending');

            $table->date('issued_at')->nullable();
            $table->date('expires_at')->nullable();

            $table->string('verification_url')->nullable();
            $table->string('document_path')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->unique([
                'regulatory_authority_id',
                'registration_number',
            ], 'product_registration_authority_number_unique');

            $table->index(['product_id', 'registration_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_registrations');
    }
};