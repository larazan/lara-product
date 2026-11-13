<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'bio',
        'password',
        'image',
        'role',
        'google_id',
        'google_type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function scopeFilter($query, array $filters)
    {
        if ($filters['search'] ?? false) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . request('search') . '%')
                    ->orWhere('email', 'like', '%' . request('search') . '%');
            });
        }

        if ($filters['user_role'] ?? false) {
            $query->where('role', request('user_role'));
        }
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function bookmarkedProducts()
    {
        return $this->belongsToMany(Product::class, 'bookmarks')
            ->withTimestamps();
    }

    public function advertiserWallet(): HasOne
    {
        return $this->hasOne(AdvertiserWallet::class);
    }

    public function adCampaigns(): HasMany
    {
        return $this->hasMany(AdCampaign::class);
    }

    public function advertiserTransactions(): HasMany
    {
        return $this->hasMany(AdvertiserTransaction::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function subscribed()
    {
        return $this->subscription && $this->subscription->isActive();
    }

    public function activeSubscription(): HasOne
    {
        return $this->hasOne(Subscription::class)
            ->where('status', 'active')
            ->where('end_date', '>', now())
            ->latestOfMany();
    }

    public function hasActiveSubscription()
    {
        return $this->subscriptions()
            ->where('status', 'active')
            ->where('end_date', '>', now())
            ->exists();
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function isPremium(): bool
    {
        return (bool) $this->activeSubscription();
    }
}
