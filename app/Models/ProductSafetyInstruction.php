<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductSafetyInstruction extends Model
{
    //
    use HasFactory;

    protected $table = 'product_safety_instructions';

    protected $fillable = [
        'product_id',
        'instruction_type',
        'step_order',
        'title',
        'content',
        'icon_code',
    ];

    protected $casts = [
        'step_order' => 'integer',
    ];

    /**
     * Relasi ke Model Product (Inverse One-to-Many)
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
