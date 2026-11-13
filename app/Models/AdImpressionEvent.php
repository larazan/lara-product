<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdImpressionEvent extends Model
{
    //
    protected $fillable = [
        'event_key',
        'campaign_id',
        'creative_id',
        'placement_id',
        'category_id',
        'date',
        'impressions',
        'billable_impressions',
        'billed_tokens',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'impressions' => 'integer',
            'billable_impressions' => 'integer',
            'billed_tokens' => 'integer',
        ];
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(AdCampaign::class);
    }

    public function creative(): BelongsTo
    {
        return $this->belongsTo(AdCreative::class);
    }

    public function placement(): BelongsTo
    {
        return $this->belongsTo(AdPlacement::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
