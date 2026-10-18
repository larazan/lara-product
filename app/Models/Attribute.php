<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Translatable\HasTranslations;

class Attribute extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $table = 'attributes';

    public $translatable = ['name', 'label'];
    //
    protected $fillable = [
        'group_id',
        'slug',
        'name',
        'label',
        'description',
        'unit',
        'type',
        'is_filterable',
        'is_comparable',
        'sort_order',
        'is_active',
        'icon_svg',
    ];

    protected $casts = [
        'name'      => 'array',   // JSON multilingual
        'label'      => 'array',   // JSON multilingual
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

    public function attributeGroup()
    {
        return $this->belongsTo(AttributeGroup::class);
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

    public function scoreConfig(): HasOne
    {
        return $this->hasOne(AttributeScore::class);
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
