<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;

    protected $table = 'attributes';
    //
    protected $fillable = [
        'group_id',
        'name',
        'label',
        'unit',
        'type',
        'is_filterable',
        'is_comparable',
        'sort_order',
    ];

    protected $casts = [
        'is_filterable' => 'boolean',
        'is_comparable' => 'boolean',
        'sort_order' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    /**
     * Attribute belongs to group
     * Example: refresh_rate -> Display
     */
    public function group()
    {
        return $this->belongsTo(AttributeGroup::class, 'group_id');
    }

    /**
     * Attribute used by many categories
     * smartphone memakai battery_mah
     * laptop memakai battery_mah
     */
    public function categories()
    {
        return $this->belongsToMany(
            Category::class,
            'category_attribute',
            'attribute_id',
            'category_id'
        )->withTimestamps();
    }

    /**
     * Nilai attribute per product
     * Xiaomi 14 => battery = 4610
     */
    // public function values()
    // {
    //     return $this->hasMany(ProductAttributeValue::class, 'attribute_id');
    // }

    /**
     * Score weight jika masih pakai profile system
     */
    // public function scoreWeights()
    // {
    //     return $this->hasMany(ScoreProfileWeight::class, 'attribute_id');
    // }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeFilterable($query)
    {
        return $query->where('is_filterable', true);
    }

    public function scopeComparable($query)
    {
        return $query->where('is_comparable', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')
                     ->orderBy('label');
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function getDisplayNameAttribute()
    {
        return $this->unit
            ? "{$this->label} ({$this->unit})"
            : $this->label;
    }

    public function getTypeBadgeAttribute()
    {
        return match ($this->type) {
            'number' => 'Number',
            'boolean' => 'Yes / No',
            'select' => 'Dropdown',
            default => 'Text',
        };
    }
}
