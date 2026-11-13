<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdPlacement extends Model
{
    //
    protected $fillable = [
        'code',
        'name',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function campaignPlacements(): HasMany
    {
        return $this->hasMany(AdCampaignPlacement::class, 'placement_id');
    }

    public function rates(): HasMany
    {
        return $this->hasMany(AdPlacementRate::class, 'placement_id');
    }

    public function impressionStats(): HasMany
    {
        return $this->hasMany(AdImpressionStat::class, 'placement_id');
    }

    public function clickStats(): HasMany
    {
        return $this->hasMany(AdClickStat::class, 'placement_id');
    }
}
