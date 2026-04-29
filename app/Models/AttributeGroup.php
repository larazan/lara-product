<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeGroup extends Model
{
    //
    use HasFactory;

    protected $table = 'attribute_groups';

    protected $fillable = [
        'category_id',
        'name',
        'label',
        'order',
        'icon',
        'score_weight',
    ];

    protected $casts = [
        'category_id'   => 'integer',
        'order'         => 'integer',
        'score_weight'  => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    /**
     * Group belongs to category
     * Example:
     * Display -> Smartphone
     * Engine -> Car
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Group has many attributes
     * Example:
     * Display -> brightness, refresh_rate, resolution
     */
    public function attributes()
    {
        return $this->hasMany(Attribute::class, 'group_id')
            ->orderBy('sort_order')
            ->orderBy('label');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /**
     * Sort by order ASC
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order')
                     ->orderBy('label');
    }

    /**
     * Only groups with weight > 0
     */
    public function scopeScorable($query)
    {
        return $query->where('score_weight', '>', 0);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /**
     * Display label with icon
     */
    public function getDisplayTitleAttribute()
    {
        return $this->icon
            ? "{$this->icon} {$this->label}"
            : $this->label;
    }

    /**
     * Total attribute count
     */
    public function getAttributesCountTextAttribute()
    {
        return $this->attributes()->count() . ' attributes';
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    /**
     * Calculate score from attributes
     * Example:
     * average all attribute scores inside group
     */
    public function calculateScore($product)
    {
        $values = $product->attributeValues
            ->whereIn('attribute_id', $this->attributes->pluck('id'));

        if ($values->count() === 0) {
            return 0;
        }

        return round($values->avg('score'), 2);
    }
}
