<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductInstruction extends Model
{
    //
    use HasFactory;

    protected $table = 'product_usage_instructions';

    protected $fillable = [
        'product_id',
        'step_number',
        'title',
        'instruction_text',
    ];

    protected $casts = [
        'step_number' => 'integer',
    ];

    /**
     * Relasi ke Model Product (Inverse One-to-Many)
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
