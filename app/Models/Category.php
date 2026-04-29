<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug; // Import HasSlug
use Spatie\Sluggable\SlugOptions; // Import SlugOptions
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    //
    use HasFactory;
    use HasSlug;
    use HasTranslations;

    protected $table = 'categories';
    public $translatable = ['name'];

    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'description',
        'is_active',
    ];

    protected $casts = [
        'name'      => 'array',   // JSON multilingual
        'is_active' => 'boolean',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    
     /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name') // Generate slug from the 'name' attribute (translatable)
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate(); // Optional: only generate on creation
    }

    

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    /**
     * Parent category
     * Example:
     * Android Phones -> Smartphones
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Child categories
     * Example:
     * Smartphones -> Android Phones, iPhone
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')
            ->orderBy('id');
    }

    /**
     * Recursive children
     */
    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }

    /**
     * Products inside category
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    /**
     * Attribute groups
     * Example:
     * Smartphone => Display, Battery, Camera
     */
    public function attributeGroups()
    {
        return $this->hasMany(AttributeGroup::class, 'category_id')
            ->orderBy('order');
    }

    /**
     * Attributes via pivot
     */
    public function attributes()
    {
        return $this->belongsToMany(
            Attribute::class,
            'category_attribute',
            'category_id',
            'attribute_id'
        )
        ->withPivot('is_required')
        ->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /**
     * Active only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Parent categories only
     */
    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Child categories only
     */
    public function scopeChildren($query)
    {
        return $query->whereNotNull('parent_id');
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /**
     * Current locale name
     * fallback: en > first item
     */
    public function getTitleAttribute()
    {
        $locale = app()->getLocale();

        return $this->name[$locale]
            ?? $this->name['en']
            ?? collect($this->name)->first();
    }

    /**
     * Full breadcrumb path
     * Example:
     * Electronics > Phones > Smartphones
     */
    public function getBreadcrumbAttribute()
    {
        $items = [];
        $current = $this;

        while ($current) {
            array_unshift($items, $current->title);
            $current = $current->parent;
        }

        return implode(' > ', $items);
    }

    /**
     * URL helper
     */
    public function getUrlAttribute()
    {
        return url('/category/' . $this->slug);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    /**
     * Check if has children
     */
    public function hasChildren(): bool
    {
        return $this->children()->exists();
    }

    /**
     * Check if root category
     */
    public function isRoot(): bool
    {
        return is_null($this->parent_id);
    }
}
