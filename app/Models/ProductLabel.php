<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductLabel extends Pivot
{
    //
    protected $table = 'product_labels';

    public $incrementing = true;

    protected $fillable = [
        'product_id',
        'label_id',
        'value',
        'notes',
        'source',
        'is_verified',
        'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'is_verified' => 'boolean',
            'verified_at' => 'datetime',
        ];
    }
}