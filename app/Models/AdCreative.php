<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdCreative extends Model
{
    //
    protected $fillable = [
        'campaign_id',
        'product_id',
        'title',
        'description',
        'image_path',
        'target_url',
        'status',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
        ];
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(AdCampaign::class, 'campaign_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function impressionStats(): HasMany
    {
        return $this->hasMany(AdImpressionStat::class, 'creative_id');
    }

    public function clickStats(): HasMany
    {
        return $this->hasMany(AdClickStat::class, 'creative_id');
    }
}
