<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdImpressionStat extends Model
{
    //
    protected $fillable = [
        'campaign_id',
        'creative_id',
        'placement_id',
        'rate_id',
        'category_id',
        'date',
        'impressions',
        'billable_impressions',
        'billed_tokens',
        'rate_per_1000',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'impressions' => 'integer',
            'billable_impressions' => 'integer',
            'billed_tokens' => 'integer',
            'rate_per_1000' => 'decimal:4',
        ];
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(AdCampaign::class, 'campaign_id');
    }

    public function creative(): BelongsTo
    {
        return $this->belongsTo(AdCreative::class, 'creative_id');
    }

    public function placement(): BelongsTo
    {
        return $this->belongsTo(AdPlacement::class, 'placement_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function rate(): BelongsTo
    {
        return $this->belongsTo(AdPlacementRate::class, 'rate_id');
    }
}
