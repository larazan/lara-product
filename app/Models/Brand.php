<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug; // Import HasSlug
use Spatie\Sluggable\SlugOptions; // Import 

class Brand extends Model
{
    use HasFactory;
    use HasSlug;
    //
    protected $fillable = [
        'name',
        'slug',
        'logo',
        'website',
        'is_active',
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name') // Generate slug from the 'name' attribute (translatable)
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate(); // Optional: only generate on creation
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'brand_id', 'id');
    }

    /**
     * Active only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
