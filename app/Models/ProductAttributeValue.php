<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttributeValue extends Model
{
    use HasFactory;

    protected $table = 'product_attribute_values';

    protected $fillable = [
        'product_id',
        'attribute_id',
        'value',
    ];

    protected $casts = [
        'product_id'   => 'integer',
        'attribute_id' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    /**
     * Value belongs to product
     * Example:
     * Xiaomi 14 -> battery_mah = 4610
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Value belongs to attribute
     * Example:
     * battery_mah
     */
    public function attribute()
    {
        return $this->belongsTo(Attribute::class, 'attribute_id');
    }

    /**
     * Shortcut ke group melalui attribute
     * Example:
     * battery_mah -> Battery
     */
    public function group()
    {
        return $this->hasOneThrough(
            AttributeGroup::class,
            Attribute::class,
            'id',          // FK on attributes
            'id',          // FK on attribute_groups
            'attribute_id',// local key on current table
            'group_id'     // local key on attributes
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /**
     * Filter by product
     */
    public function scopeForProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    /**
     * Filter by attribute
     */
    public function scopeForAttribute($query, $attributeId)
    {
        return $query->where('attribute_id', $attributeId);
    }

    /**
     * With eager load relations
     */
    public function scopeWithDetails($query)
    {
        return $query->with([
            'attribute.group',
            'product',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /**
     * Label attribute
     * Example:
     * Battery Capacity
     */
    public function getLabelAttribute()
    {
        return $this->attribute?->label;
    }

    /**
     * Unit attribute
     * Example:
     * mAh
     */
    public function getUnitAttribute()
    {
        return $this->attribute?->unit;
    }

    /**
     * Full formatted value
     * Example:
     * 4610 mAh
     */
    public function getFormattedValueAttribute()
    {
        if (!$this->value) {
            return '-';
        }

        $unit = $this->attribute?->unit;

        return $unit
            ? "{$this->value} {$unit}"
            : $this->value;
    }

    /**
     * Group label
     * Example:
     * Battery
     */
    public function getGroupLabelAttribute()
    {
        return $this->attribute?->group?->label;
    }

    /**
     * Numeric value helper
     */
    public function getNumericValueAttribute()
    {
        return is_numeric($this->value)
            ? (float) $this->value
            : null;
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    /**
     * Check if numeric type
     */
    public function isNumeric(): bool
    {
        return $this->attribute?->type === 'number'
            && is_numeric($this->value);
    }

    /**
     * Check if boolean type
     */
    public function isBoolean(): bool
    {
        return $this->attribute?->type === 'boolean';
    }

    /**
     * Boolean text helper
     */
    public function booleanText(): string
    {
        return filter_var($this->value, FILTER_VALIDATE_BOOLEAN)
            ? 'Yes'
            : 'No';
    }
}
