<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductRegistration extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'product_id',
        'regulatory_authority_id',
        'registration_number',
        'registration_status',
        'issued_at',
        'expires_at',
        'verification_url',
        'document_path',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'issued_at' => 'date',
            'expires_at' => 'date',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function authority(): BelongsTo
    {
        return $this->belongsTo(
            RegulatoryAuthority::class,
            'regulatory_authority_id'
        );
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->expires_at !== null
            && $this->expires_at->isPast();
    }
}