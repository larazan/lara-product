<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan_id',
        'invoice_number',
        'amount',
        'payment_method',
        'midtrans_order_id',
        'snap_token',
        'payment_type',
        'proof_of_payment',
        'status',
        'paid_at',
        'payload',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'payload' => 'array',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    public function isPaid()
    {
        return $this->status === 'paid';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    /*
    |--------------------------------------------------------------------------
    | STATUS UPDATE (Midtrans)
    |--------------------------------------------------------------------------
    */

    public function markAsPaid($paymentType = null, $payload = null)
    {
        $this->update([
            'status' => 'paid',
            'payment_type' => $paymentType,
            'paid_at' => now(),
            'payload' => $payload,
        ]);
    }

    public function markAsFailed()
    {
        $this->update([
            'status' => 'failed',
        ]);
    }

    public function markAsExpired()
    {
        $this->update([
            'status' => 'expired',
        ]);
    }
}
