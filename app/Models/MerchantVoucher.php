<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MerchantVoucher extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'initial_voucher_id',
        'merchant_id',
        'code',
        'status',
        'redeemed_at',
        'redeemed_by_merchant_user_id',
    ];

    protected $casts = [
        'redeemed_at' => 'datetime',
    ];

    /**
     * Get the initial voucher this merchant voucher was generated from.
     */
    public function initialVoucher(): BelongsTo
    {
        return $this->belongsTo(InitialVoucher::class);
    }

    /**
     * Get the merchant this voucher belongs to.
     */
    public function merchant(): BelongsTo
    {
        return $this->belongsTo(Merchant::class);
    }

    /**
     * Get the merchant user who redeemed this voucher.
     */
    public function redeemedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'redeemed_by_merchant_user_id');
    }

    /**
     * Scope to get only active vouchers.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'ACTIVE');
    }

    /**
     * Scope to get only redeemed vouchers.
     */
    public function scopeRedeemed($query)
    {
        return $query->where('status', 'REDEEMED');
    }
}
