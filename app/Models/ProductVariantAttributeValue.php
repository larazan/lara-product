<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariantAttributeValue extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'product_variant_id',
        'attribute_id',
        'value',
    ];

    public function variant(): BelongsTo
    {
        return $this->belongsTo(
            ProductVariant::class,
            'product_variant_id'
        );
    }

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class);
    }

    public function getFormattedValueAttribute(): string
    {
        if ($this->value === null || $this->value === '') {
            return '-';
        }

        $unit = $this->attribute?->unit;

        return $unit
            ? "{$this->value} {$unit}"
            : $this->value;
    }
}
