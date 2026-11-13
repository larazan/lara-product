<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subscription extends Model
{
    //
    use HasFactory;
    //
    // protected $fillable = [
    //     'user_id',
    //     'plan',
    //     'starts_at',
    //     'ends_at',
    //     'payment_provider',
    //     'payment_id',
    //     'paid_at',
    //     'status',
    // ];

    protected $fillable = [
        'user_id',
        'plan_id',
        'order_id',
        'status',
        'start_date',
        'end_date',
        'canceled_at',
        'meta',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'canceled_at' => 'datetime',
        'meta' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    public function isActive()
    {
        return $this->status === 'active' && $this->end_date > now();
    }

    public function isExpired()
    {
        return $this->end_date <= now();
    }

    /*
    |--------------------------------------------------------------------------
    | ACTIONS
    |--------------------------------------------------------------------------
    */

    public function activate()
    {
        $this->update([
            'status' => 'active',
            'start_date' => now(),
            'end_date' => now()->addDays($this->plan->duration_days),
        ]);
    }

    public function cancel()
    {
        $this->update([
            'status' => 'canceled',
            'canceled_at' => now(),
        ]);
    }

    public function expire()
    {
        $this->update([
            'status' => 'expired',
        ]);
    }
}
