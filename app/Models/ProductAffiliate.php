<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductAffiliate extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'product_id',
        'merchant',
        'store_name',
        'url',
        'price',
        'currency',
        'is_active',
        'sort_order',
        'last_checked_at',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'last_checked_at' => 'datetime',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
