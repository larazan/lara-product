<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvertisingSegment extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'title',
        'size',
        'original',
        'is_active',
    ];

	public function advertisings()
    {
        return $this->hasMany(Advertising::class, 'segment_id');
    }
}
