<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdCampaign extends Model
{
    //
    protected $fillable = [
        'user_id',
        'name',
        'status',
        'budget_tokens',
        'spent_tokens',
        'starts_at',
        'ends_at',
        'approved_at',
        'approved_by',
        'rejection_reason',
    ];

    protected function casts(): array
    {
        return [
            'budget_tokens' => 'integer',
            'spent_tokens' => 'integer',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'approved_at' => 'datetime',
        ];
    }

    public function advertiser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function creatives(): HasMany
    {
        return $this->hasMany(AdCreative::class, 'campaign_id');
    }

    public function placements(): HasMany
    {
        return $this->hasMany(AdCampaignPlacement::class, 'campaign_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(AdvertiserTransaction::class, 'campaign_id');
    }

    public function impressionStats(): HasMany
    {
        return $this->hasMany(AdImpressionStat::class, 'campaign_id');
    }

    public function clickStats(): HasMany
    {
        return $this->hasMany(AdClickStat::class, 'campaign_id');
    }

    public function scopeActive($query)
    {
        return $query
            ->where('status', 'active')
            ->where(function ($query) {
                $query->whereNull('starts_at')
                    ->orWhere('starts_at', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('ends_at')
                    ->orWhere('ends_at', '>', now());
            });
    }
}
