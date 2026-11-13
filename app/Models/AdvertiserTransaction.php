<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdvertiserTransaction extends Model
{
    //
    protected $fillable = [
        'wallet_id',
        'user_id',
        'campaign_id',
        'type',
        'token_amount',
        'balance_before',
        'balance_after',
        'reference_type',
        'reference_id',
        'idempotency_key',
        'metadata',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'token_amount' => 'integer',
            'balance_before' => 'integer',
            'balance_after' => 'integer',
            'metadata' => 'array',
        ];
    }

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(AdvertiserWallet::class, 'wallet_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(AdCampaign::class);
    }
}
