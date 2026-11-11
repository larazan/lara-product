<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Translatable\HasTranslations;
use Spatie\Sluggable\HasSlug; // Import HasSlug
use Spatie\Sluggable\SlugOptions;

class Label extends Model
{
    //
    use HasFactory;
    use HasSlug;
    use HasTranslations;

    protected $fillable = [
        'label_group_id',
        'name',
        'slug',
        'description',
        'icon',
        'label_type',
        'severity',
        'is_active',
    ];

    public $translatable = [
        'name',
        'description',
    ];

    protected function casts(): array
    {
        return [
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

    public function group(): BelongsTo
    {
        return $this->belongsTo(LabelGroup::class, 'label_group_id');
    }

    // public function categories(): BelongsToMany
    // {
    //     return $this->belongsToMany(
    //         Category::class,
    //         'category_labels'
    //     )->withPivot([
    //         'id',
    //         'is_required',
    //         'is_active',
    //         'sort_order',
    //     ])->withTimestamps();
    // }

    // public function products(): BelongsToMany
    // {
    //     return $this->belongsToMany(
    //         Product::class,
    //         'product_labels'
    //     )->withPivot([
    //         'id',
    //         'value',
    //         'notes',
    //         'source',
    //         'is_verified',
    //         'verified_at',
    //     ])->withTimestamps();
    // }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(
            Category::class,
            'category_labels'
        )
            ->using(CategoryLabel::class)
            ->withPivot([
                'id',
                'is_required',
                'is_active',
                'sort_order',
            ])
            ->withTimestamps();
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(
            Product::class,
            'product_labels'
        )
            ->using(ProductLabel::class)
            ->withPivot([
                'id',
                'value',
                'notes',
                'source',
                'is_verified',
                'verified_at',
            ])
            ->withTimestamps();
    }
}
