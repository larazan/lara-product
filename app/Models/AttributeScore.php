<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttributeScore extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'attribute_id',
        'category_attribute_id',
        'weight',
        'higher_is_better',
        'scoring_method',
        'scoring_config',
    ];

    protected function casts(): array
    {
        return [
            'weight' => 'decimal:2',
            'higher_is_better' => 'boolean',
        ];
    }

    public function categoryAttribute(): BelongsTo
    {
        return $this->belongsTo(CategoryAttribute::class);
    }

    /**
     * Atribut yang memiliki konfigurasi penilaian.
     */
    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class);
    }
}
