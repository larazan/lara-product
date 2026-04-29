<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductFaq extends Model
{
    //
    protected $guarded = [];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
