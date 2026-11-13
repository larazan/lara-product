<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdClickStat extends Model
{
    //
    protected $fillable = [
        'campaign_id',
        'creative_id',
        'placement_id',
        'date',
        'clicks',
        'unique_clicks',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'clicks' => 'integer',
            'unique_clicks' => 'integer',
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
}
