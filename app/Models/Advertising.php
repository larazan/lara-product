<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advertising extends Model
{
    //
     use HasFactory;

    protected $fillable = [
        'segment_id',
        'title',
        'start',
        'end',
        'url',
        'original',
        'small',
        'is_active',
        'description',
        'views',
        'clicks',
    ];

    // public function scopeActive($query)
    // {
    //     return $query->where('status', 'active');
    // }

     // Scopes for easy querying
     public function scopeActive($query)
     {
         return $query->where('status', 'active')
                      ->where(function ($q) {
                          $q->whereNull('start')
                            ->orWhere('start', '<=', now());
                      })
                      ->where(function ($q) {
                          $q->whereNull('end')
                            ->orWhere('end', '>=', now());
                      });
     }

    public function segment()
	{
		return $this->belongsTo(AdvertisingSegment::class, 'segment_id');
	}
}
