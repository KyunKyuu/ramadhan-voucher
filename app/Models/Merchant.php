<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Merchant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'email',
        'password',
        'logo_url',
        'is_active',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the offer for this merchant.
     */
    public function offer(): HasOne
    {
        return $this->hasOne(MerchantOffer::class)->where('is_active', true);
    }

    /**
     * Get all offers for this merchant.
     */
    public function offers(): HasMany
    {
        return $this->hasMany(MerchantOffer::class);
    }

    /**
     * Get the merchant vouchers for this merchant.
     */
    public function merchantVouchers(): HasMany
    {
        return $this->hasMany(MerchantVoucher::class);
    }

    /**
     * Get the users (merchant staff) for this merchant.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Scope to get only active merchants.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
