<?php

namespace App\Services\Advertising;

use App\Models\AdvertiserTransaction;
use App\Models\AdvertiserWallet;
use App\Models\AdCampaign;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AdTokenService
{
    /**
     * Menambahkan token ke wallet.
     * Panggil hanya setelah pembayaran terverifikasi.
     */
    public function credit(
        int $userId,
        int $tokens,
        string $type = 'purchase',
        ?string $referenceType = null,
        ?string $referenceId = null,
        ?string $idempotencyKey = null,
        ?string $description = null,
        array $metadata = [],
    ): AdvertiserTransaction {
        if ($tokens <= 0) {
            throw ValidationException::withMessages([
                'tokens' => 'Jumlah token harus lebih dari nol.',
            ]);
        }

        return DB::transaction(function () use (
            $userId,
            $tokens,
            $type,
            $referenceType,
            $referenceId,
            $idempotencyKey,
            $description,
            $metadata
        ) {
            $wallet = AdvertiserWallet::firstOrCreate(
                ['user_id' => $userId]
            );

            $wallet = AdvertiserWallet::whereKey($wallet->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($idempotencyKey) {
                $existing = AdvertiserTransaction::where(
                    'idempotency_key',
                    $idempotencyKey
                )->first();

                if ($existing) {
                    if ($existing->wallet_id !== $wallet->id) {
                        throw ValidationException::withMessages([
                            'transaction' => 'Idempotency key sudah digunakan.',
                        ]);
                    }

                    return $existing;
                }
            }

            $before = $wallet->balance_tokens;
            $after = $before + $tokens;

            $wallet->balance_tokens = $after;

            if ($type === 'purchase') {
                $wallet->lifetime_purchased += $tokens;
            }

            $wallet->save();

            return AdvertiserTransaction::create([
                'wallet_id' => $wallet->id,
                'user_id' => $userId,
                'type' => $type,
                'token_amount' => $tokens,
                'balance_before' => $before,
                'balance_after' => $after,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'idempotency_key' => $idempotencyKey,
                'description' => $description,
                'metadata' => $metadata,
            ]);
        }, 3);
    }

    /**
     * Memotong token untuk impression iklan.
     *
     * Panggil di dalam transaction yang sudah
     * mengunci campaign.
     */
    public function debitForCampaign(
        AdCampaign $campaign,
        int $tokens,
        ?string $idempotencyKey = null,
        array $metadata = [],
    ): AdvertiserTransaction {
        if ($tokens <= 0) {
            throw ValidationException::withMessages([
                'tokens' => 'Jumlah token tidak valid.',
            ]);
        }

        $wallet = AdvertiserWallet::where(
            'user_id',
            $campaign->user_id
        )
            ->lockForUpdate()
            ->first();

        if (!$wallet || $wallet->balance_tokens < $tokens) {
            throw ValidationException::withMessages([
                'balance' => 'Saldo token tidak mencukupi.',
            ]);
        }

        $remainingBudget =
            $campaign->budget_tokens - $campaign->spent_tokens;

        if ($remainingBudget < $tokens) {
            throw ValidationException::withMessages([
                'budget' => 'Budget kampanye tidak mencukupi.',
            ]);
        }

        if ($idempotencyKey) {
            $existing = AdvertiserTransaction::where(
                'idempotency_key',
                $idempotencyKey
            )->first();

            if ($existing) {
                return $existing;
            }
        }

        $before = $wallet->balance_tokens;
        $after = $before - $tokens;

        $wallet->balance_tokens = $after;
        $wallet->save();

        $campaign->spent_tokens += $tokens;
        $campaign->save();

        return AdvertiserTransaction::create([
            'wallet_id' => $wallet->id,
            'user_id' => $campaign->user_id,
            'campaign_id' => $campaign->id,
            'type' => 'spend',
            'token_amount' => -$tokens,
            'balance_before' => $before,
            'balance_after' => $after,
            'idempotency_key' => $idempotencyKey,
            'description' => 'Native advertising impression charge',
            'metadata' => $metadata,
        ]);
    }

    /**
     * Refund token ke wallet.
     */
    public function refund(
        AdCampaign $campaign,
        int $tokens,
        ?string $idempotencyKey = null,
        array $metadata = [],
    ): AdvertiserTransaction {
        if ($tokens <= 0) {
            throw ValidationException::withMessages([
                'tokens' => 'Jumlah refund tidak valid.',
            ]);
        }

        return DB::transaction(function () use (
            $campaign,
            $tokens,
            $idempotencyKey,
            $metadata
        ) {
            $lockedCampaign = AdCampaign::whereKey($campaign->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($idempotencyKey) {
                $existing = AdvertiserTransaction::where(
                    'idempotency_key',
                    $idempotencyKey
                )->first();

                if ($existing) {
                    return $existing;
                }
            }

            if ($lockedCampaign->spent_tokens < $tokens) {
                throw ValidationException::withMessages([
                    'refund' => 'Jumlah refund melebihi pemakaian kampanye.',
                ]);
            }

            $wallet = AdvertiserWallet::where(
                'user_id',
                $lockedCampaign->user_id
            )
                ->lockForUpdate()
                ->firstOrFail();

            $before = $wallet->balance_tokens;
            $after = $before + $tokens;

            $wallet->balance_tokens = $after;
            $wallet->save();

            $lockedCampaign->spent_tokens -= $tokens;
            $lockedCampaign->save();

            return AdvertiserTransaction::create([
                'wallet_id' => $wallet->id,
                'user_id' => $lockedCampaign->user_id,
                'campaign_id' => $lockedCampaign->id,
                'type' => 'refund',
                'token_amount' => $tokens,
                'balance_before' => $before,
                'balance_after' => $after,
                'idempotency_key' => $idempotencyKey,
                'description' => 'Native advertising token refund',
                'metadata' => $metadata,
            ]);
        }, 3);
    }
}
