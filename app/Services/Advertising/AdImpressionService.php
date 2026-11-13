<?php

namespace App\Services\Advertising;

use App\Models\AdCampaign;
use App\Models\AdCreative;
use App\Models\AdImpressionEvent;
use App\Models\AdImpressionStat;
use App\Models\AdPlacement;
use App\Models\AdPlacementRate;
use App\Models\AdvertiserWallet;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AdImpressionService
{
    public function __construct(
        protected AdTokenService $tokenService
    ) {}

    /**
     * Mencatat impression yang sudah lolos validasi.
     *
     * $eventKey harus unik untuk satu batch event.
     */
    public function record(
        int $campaignId,
        int $creativeId,
        int $placementId,
        ?int $categoryId,
        int $impressions,
        string $eventKey,
    ): array {
        if ($impressions <= 0 || $impressions > 1000) {
            throw ValidationException::withMessages([
                'impressions' => 'Jumlah impression harus antara 1 sampai 1000.',
            ]);
        }

        if (strlen($eventKey) > 100 || trim($eventKey) === '') {
            throw ValidationException::withMessages([
                'event_key' => 'Event key tidak valid.',
            ]);
        }

        return DB::transaction(function () use (
            $campaignId,
            $creativeId,
            $placementId,
            $categoryId,
            $impressions,
            $eventKey
        ) {
            // 1. Lock campaign agar proses billing tidak berjalan
            // bersamaan untuk kampanye yang sama.
            $campaign = AdCampaign::whereKey($campaignId)
                ->lockForUpdate()
                ->firstOrFail();

            // 2. Cegah event yang sama ditagihkan dua kali.
            $existingEvent = AdImpressionEvent::where(
                'event_key',
                $eventKey
            )->first();

            if ($existingEvent) {
                return [
                    'duplicate' => true,
                    'impressions' => $existingEvent->impressions,
                    'billable_impressions' =>
                    $existingEvent->billable_impressions,
                    'billed_tokens' => $existingEvent->billed_tokens,
                ];
            }

            // 3. Validasi status dan periode kampanye.
            if (!$this->campaignIsEligible($campaign)) {
                throw ValidationException::withMessages([
                    'campaign' => 'Kampanye tidak aktif atau tidak tersedia.',
                ]);
            }

            // 4. Validasi creative.
            $creative = AdCreative::whereKey($creativeId)
                ->where('campaign_id', $campaign->id)
                ->where('status', 'approved')
                ->first();

            if (!$creative) {
                throw ValidationException::withMessages([
                    'creative' => 'Materi iklan tidak valid atau belum disetujui.',
                ]);
            }

            // 5. Validasi placement.
            $placement = AdPlacement::whereKey($placementId)
                ->where('is_active', true)
                ->first();

            if (!$placement) {
                throw ValidationException::withMessages([
                    'placement' => 'Placement iklan tidak tersedia.',
                ]);
            }

            // 6. Pastikan kampanye memilih placement dan kategori.
            $targeting = $campaign->placements()
                ->where('placement_id', $placementId)
                ->where('is_active', true)
                ->where(function ($query) use ($categoryId) {
                    $query->whereNull('category_id');

                    if ($categoryId !== null) {
                        $query->orWhere('category_id', $categoryId);
                    }
                })
                ->exists();

            if (!$targeting) {
                throw ValidationException::withMessages([
                    'targeting' => 'Placement atau kategori tidak sesuai.',
                ]);
            }

            // 7. Cari tarif kategori spesifik terlebih dahulu.
            // Jika tidak tersedia, gunakan tarif global.
            $rate = AdPlacementRate::query()
                ->where('placement_id', $placementId)
                ->effective()
                ->where(function ($query) use ($categoryId) {
                    $query->whereNull('category_id');

                    if ($categoryId !== null) {
                        $query->orWhere('category_id', $categoryId);
                    }
                })
                ->orderByRaw('category_id IS NULL ASC')
                ->orderByDesc('starts_at')
                ->lockForUpdate()
                ->first();

            if (!$rate) {
                throw ValidationException::withMessages([
                    'rate' => 'Tarif placement belum tersedia.',
                ]);
            }

            // 8. Ambil statistik akumulasi untuk rate ini.
            $previousImpressions = (int) AdImpressionStat::query()
                ->where('campaign_id', $campaign->id)
                ->where('creative_id', $creativeId)
                ->where('placement_id', $placementId)
                ->where('rate_id', $rate->id)
                ->when(
                    $categoryId === null,
                    fn($query) => $query->whereNull('category_id'),
                    fn($query) => $query->where('category_id', $categoryId)
                )
                ->sum('billable_impressions');

            $previousBilledTokens = (int) AdImpressionStat::query()
                ->where('campaign_id', $campaign->id)
                ->where('creative_id', $creativeId)
                ->where('placement_id', $placementId)
                ->where('rate_id', $rate->id)
                ->when(
                    $categoryId === null,
                    fn($query) => $query->whereNull('category_id'),
                    fn($query) => $query->where('category_id', $categoryId)
                )
                ->sum('billed_tokens');

            // 9. Periksa sisa saldo dan budget.
            $wallet = AdvertiserWallet::where(
                'user_id',
                $campaign->user_id
            )
                ->lockForUpdate()
                ->first();

            $availableTokens = min(
                (int) ($wallet?->balance_tokens ?? 0),
                max(0, $campaign->budget_tokens - $campaign->spent_tokens)
            );

            // 10. Hitung jumlah impression yang masih dapat ditagihkan.
            $billableImpressions = $this->affordableImpressions(
                requested: $impressions,
                previousImpressions: $previousImpressions,
                previousBilledTokens: $previousBilledTokens,
                availableTokens: $availableTokens,
                rate: (string) $rate->tokens_per_1000_impressions,
            );

            $newTotalImpressions =
                $previousImpressions + $billableImpressions;

            $newTotalTokens = $this->calculateTokens(
                $newTotalImpressions,
                (string) $rate->tokens_per_1000_impressions
            );

            $tokensToDebit = max(
                0,
                $newTotalTokens - $previousBilledTokens
            );

            $date = now()->toDateString();

            // 11. Simpan event agar tidak ditagihkan ulang.
            $event = AdImpressionEvent::create([
                'event_key' => $eventKey,
                'campaign_id' => $campaign->id,
                'creative_id' => $creativeId,
                'placement_id' => $placementId,
                'category_id' => $categoryId,
                'date' => $date,
                'impressions' => $impressions,
                'billable_impressions' => $billableImpressions,
                'billed_tokens' => $tokensToDebit,
            ]);

            // 12. Debit token.
            if ($tokensToDebit > 0) {
                $this->tokenService->debitForCampaign(
                    campaign: $campaign,
                    tokens: $tokensToDebit,
                    idempotencyKey: 'impression-' . $event->id,
                    metadata: [
                        'event_id' => $event->id,
                        'placement_id' => $placementId,
                        'creative_id' => $creativeId,
                        'rate_id' => $rate->id,
                        'billable_impressions' => $billableImpressions,
                        'rate_per_1000' => $rate->tokens_per_1000_impressions,
                    ]
                );
            }

            // 13. Update statistik harian.
            $statQuery = AdImpressionStat::query()
                ->where('campaign_id', $campaign->id)
                ->where('creative_id', $creativeId)
                ->where('placement_id', $placementId)
                ->where('rate_id', $rate->id)
                ->whereDate('date', $date);

            if ($categoryId === null) {
                $statQuery->whereNull('category_id');
            } else {
                $statQuery->where('category_id', $categoryId);
            }

            $stat = $statQuery->first();

            if (!$stat) {
                $stat = new AdImpressionStat([
                    'campaign_id' => $campaign->id,
                    'creative_id' => $creativeId,
                    'placement_id' => $placementId,
                    'rate_id' => $rate->id,
                    'category_id' => $categoryId,
                    'date' => $date,
                    'impressions' => 0,
                    'billable_impressions' => 0,
                    'billed_tokens' => 0,
                    'rate_per_1000' => $rate->tokens_per_1000_impressions,
                ]);
            }

            $stat->impressions += $impressions;
            $stat->billable_impressions += $billableImpressions;
            $stat->billed_tokens += $tokensToDebit;
            $stat->save();

            return [
                'duplicate' => false,
                'impressions' => $impressions,
                'billable_impressions' => $billableImpressions,
                'unbillable_impressions' => $impressions - $billableImpressions,
                'billed_tokens' => $tokensToDebit,
                'remaining_wallet_tokens' =>
                max(0, (int) ($wallet?->balance_tokens ?? 0) - $tokensToDebit),
                'rate' => $rate->tokens_per_1000_impressions,
                'event_id' => $event->id,
            ];
        }, 3);
    }

    /**
     * Memeriksa kampanye aktif dan periode penayangan.
     */
    protected function campaignIsEligible(
        AdCampaign $campaign
    ): bool {
        if ($campaign->status !== 'active') {
            return false;
        }

        if (
            $campaign->starts_at &&
            $campaign->starts_at->isFuture()
        ) {
            return false;
        }

        if (
            $campaign->ends_at &&
            $campaign->ends_at->isPast()
        ) {
            return false;
        }

        if (
            $campaign->spent_tokens >= $campaign->budget_tokens
        ) {
            return false;
        }

        return true;
    }

    /**
     * Menghitung token dengan pembulatan kumulatif ke bawah.
     *
     * Contoh:
     * 1000 impressions x 1 token = 1 token.
     * 1000 impressions x 4 token = 4 token.
     */
    protected function calculateTokens(
        int $impressions,
        string $rate
    ): int {
        $rateUnits = (int) round((float) $rate * 10000);

        return intdiv(
            $impressions * $rateUnits,
            10_000_000
        );
    }

    /**
     * Menghitung maksimal impression yang masih bisa dibayar.
     * Menggunakan binary search agar tidak perlu loop ribuan kali.
     */
    protected function affordableImpressions(
        int $requested,
        int $previousImpressions,
        int $previousBilledTokens,
        int $availableTokens,
        string $rate
    ): int {
        $low = 0;
        $high = $requested;
        $result = 0;

        while ($low <= $high) {
            $mid = intdiv($low + $high, 2);

            $totalTokens = $this->calculateTokens(
                $previousImpressions + $mid,
                $rate
            );

            $additionalTokens = max(
                0,
                $totalTokens - $previousBilledTokens
            );

            if ($additionalTokens <= $availableTokens) {
                $result = $mid;
                $low = $mid + 1;
            } else {
                $high = $mid - 1;
            }
        }

        return $result;
    }
}
