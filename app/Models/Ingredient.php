<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Ingredient extends Model
{
    //
    use HasFactory;

    protected $table = 'ingredients';

    protected $fillable = [
        'name',
        'inci_name',
        'safety_rating',
        'description',
    ];

    protected $casts = [
        'safety_rating' => 'integer',
    ];

    /**
     * Relasi ke Model Product (Many-to-Many melalui tabel pivot product_ingredients)
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_ingredients')
            ->withPivot(['is_active_ingredient', 'percentage'])
            ->withTimestamps();
    }
}
