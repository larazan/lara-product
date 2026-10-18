<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug; // Import HasSlug
use Spatie\Sluggable\SlugOptions; // Import 

class Company extends Model
{
    use HasFactory;
    use HasSlug;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'legal_name',
        'description',
        'logo',
        'website',
        'email',
        'country_id',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function brands(): HasMany
    {
        return $this->hasMany(Brand::class);
    }

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
        return $this->hasMany(Product::class, 'company_id', 'id');
    }

    /**
     * Active only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
