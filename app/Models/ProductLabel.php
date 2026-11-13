<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductLabel extends Pivot
{
    //
    protected $table = 'product_labels';

    public $incrementing = true;

    protected $fillable = [
        'product_id',
        'label_id',
        'is_present',
        'value',
        'notes',
        'source',
        'is_verified',
        'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'is_present' => 'boolean',
            'is_verified' => 'boolean',
            'verified_at' => 'datetime',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function label(): BelongsTo
    {
        return $this->belongsTo(Label::class);
    }
}
