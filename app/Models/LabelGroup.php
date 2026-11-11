<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;
use Spatie\Sluggable\HasSlug; // Import HasSlug
use Spatie\Sluggable\SlugOptions;

class LabelGroup extends Model
{
    //
    use HasFactory;
    use HasSlug;
    use HasTranslations;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'sort_order',
        'is_active',
    ];

    public $translatable = [
        'name',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
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

    public function labels(): HasMany
    {
        return $this->hasMany(Label::class);
    }
}
