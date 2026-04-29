<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryAttribute extends Model
{
    use HasFactory;

    protected $table = 'category_attribute';

    protected $fillable = [
        'category_id',
        'attribute_id',
        'is_required',
    ];

    protected $casts = [
        'category_id'  => 'integer',
        'attribute_id' => 'integer',
        'is_required'  => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    /**
     * Pivot belongs to Category
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Pivot belongs to Attribute
     */
    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    /**
     * Shortcut ke group melalui attribute
     */
    public function group()
    {
        return $this->hasOneThrough(
            AttributeGroup::class,
            Attribute::class,
            'id',        // Foreign key on attributes table
            'id',        // Foreign key on attribute_groups
            'attribute_id', // Local key on category_attribute
            'group_id'   // Local key on attributes
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /**
     * Only required attributes
     */
    public function scopeRequired($query)
    {
        return $query->where('is_required', true);
    }

    /**
     * Optional attributes
     */
    public function scopeOptional($query)
    {
        return $query->where('is_required', false);
    }

    /**
     * Filter by category
     */
    public function scopeForCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /**
     * Label attribute
     */
    public function getAttributeLabelAttribute()
    {
        return $this->attribute?->label;
    }

    /**
     * Nama group
     */
    public function getGroupLabelAttribute()
    {
        return $this->attribute?->group?->label;
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    /**
     * Required text
     */
    public function requiredText(): string
    {
        return $this->is_required ? 'Required' : 'Optional';
    }
}
