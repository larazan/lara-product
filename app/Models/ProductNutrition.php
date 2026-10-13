<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductNutrition extends Model
{
    //
    use HasFactory;

    protected $table = 'product_nutritions';

    protected $fillable = [
        'product_id',
        'serving_size',
        'calories',
        'total_fat',
        'saturated_fat',
        'carbohydrates',
        'sugars',
        'proteins',
        'sodium',
        'additional_nutrients',
    ];

    protected $casts = [
        'calories' => 'decimal:2',
        'total_fat' => 'decimal:2',
        'saturated_fat' => 'decimal:2',
        'carbohydrates' => 'decimal:2',
        'sugars' => 'decimal:2',
        'proteins' => 'decimal:2',
        'sodium' => 'decimal:2',
        'additional_nutrients' => 'array', // Otomatis cast JSON ke PHP Array & sebaliknya
    ];

    /**
     * Relasi ke Model Product (Inverse One-to-Many / One-to-One)
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
