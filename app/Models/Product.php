<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'user_id',
        'category_id',
        'brand_id',
        'company_id',
        'country_id',
        'name',
        'slug',
        'description',
        'year',
        'is_featured',
        'is_active',
        'published_at',
    ];

    protected $casts = [
        'year'         => 'integer',
        'is_featured'  => 'boolean',
        'is_active'    => 'boolean',
        'published_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    /**
     * Creator / submitter product
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Product category
     * Example: Smartphone, Car
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Brand
     * Example: Samsung, Toyota
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Company / parent company
     * Example: Samsung Electronics
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Country / market version
     * Example: Indonesia, Global, China
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Product specs values
     * Example:
     * battery = 5000
     * screen_size = 6.7
     */
    public function attributeValues()
    {
        return $this->hasMany(ProductAttributeValue::class)
            ->with('attribute.group');
    }

    /**
     * Reviews by users
     */
    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    /**
     * Product images
     */
    public function images()
    {
        return $this->hasMany(ProductImage::class)
            ->orderBy('sort_order');
    }

     /**
     * Product faqs
     */
    public function faqs()
    {
        return $this->hasMany(ProductFaq::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /**
     * Active products only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Published only
     */
    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * Featured products
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * By category
     */
    public function scopeCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * By brand
     */
    public function scopeBrand($query, $brandId)
    {
        return $query->where('brand_id', $brandId);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /**
     * URL helper
     */
    public function getUrlAttribute()
    {
        return url('/product/' . $this->slug);
    }

    /**
     * Product status text
     */
    public function getStatusTextAttribute()
    {
        return $this->is_active ? 'Active' : 'Inactive';
    }

    /**
     * Thumbnail image
     */
    public function getThumbnailAttribute()
    {
        return $this->images()->where('is_primary', true)->first()?->image
            ?? $this->images()->first()?->image
            ?? null;
    }

    /**
     * Average rating
     */
    public function getAverageRatingAttribute()
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }

    /**
     * Total reviews
     */
    public function getReviewsCountAttribute()
    {
        return $this->reviews()->count();
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    /**
     * Get attribute value by name
     * Example:
     * $product->spec('battery')
     */
    public function spec($name)
    {
        $item = $this->attributeValues
            ->firstWhere('attribute.name', $name);

        return $item?->value;
    }

    /**
     * Get formatted attribute value
     */
    public function specFormatted($name)
    {
        $item = $this->attributeValues
            ->firstWhere('attribute.name', $name);

        return $item?->formatted_value;
    }

    /**
     * Check published
     */
    public function isPublished(): bool
    {
        return $this->published_at &&
               $this->published_at <= now();
    }

    /**
     * Calculate overall score from groups
     */
    public function calculateScore(): float
    {
        $groups = $this->category
            ->attributeGroups()
            ->with('attributes')
            ->get();

        if ($groups->isEmpty()) {
            return 0;
        }

        $total = 0;
        $weight = 0;

        foreach ($groups as $group) {
            $values = $this->attributeValues
                ->whereIn(
                    'attribute_id',
                    $group->attributes->pluck('id')
                )
                ->filter(fn ($v) => is_numeric($v->value));

            if ($values->count() === 0) {
                continue;
            }

            $score = $values->avg('value');

            $total += $score * $group->score_weight;
            $weight += $group->score_weight;
        }

        return $weight > 0
            ? round($total / $weight, 2)
            : 0;
    }
}
