<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdPackage extends Model
{
    //
    protected $fillable = [
        'name',
        'token_amount',
        'price',
        'currency',
        'description',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'token_amount' => 'integer',
            'price' => 'decimal:2',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }
}
