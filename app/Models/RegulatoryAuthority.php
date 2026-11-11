<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RegulatoryAuthority extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'country_id',
        'name',
        'code',
        'description',
        'website',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(
            ProductRegistration::class
        );
    }
}