<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Merchant extends Model
{
    use HasFactory, SoftDeletes;

    protected static function booted()
    {
        static::deleted(function ($merchant) {
            $merchant->offers()->delete();
            $merchant->merchantVouchers()->delete();
            $merchant->users()->delete();
        });
    }

    protected $fillable = [
        'name',
        'slug',
        'email',
        'password',
        'logo_url',
        'is_active',
        'voucher_template',
        'address',
        'website',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the logo URL.
     * If it's a full URL, return it. Otherwise, assume it's a local file and wrap with asset().
     */
    public function getLogoUrlAttribute($value)
    {
        if (empty($value)) {
            return null;
        }

        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }

        return asset($value);
    }

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
