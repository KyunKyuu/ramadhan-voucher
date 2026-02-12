<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Claim extends Model
{
    use HasFactory;

    protected $fillable = [
        'initial_voucher_id',
        'name',
        'email',
        'phone',
        'zakat_fitrah_amount',
        'infaq_amount',
        'sodaqoh_amount',
        'public_token',
    ];

    protected $casts = [
        'zakat_fitrah_amount' => 'decimal:2',
        'infaq_amount' => 'decimal:2',
        'sodaqoh_amount' => 'decimal:2',
    ];

    /**
     * Get the total donation amount.
     */
    public function getTotalDonationAmountAttribute(): float
    {
        return $this->zakat_fitrah_amount + $this->infaq_amount + $this->sodaqoh_amount;
    }

    /**
     * Get the initial voucher this claim is for.
     */
    public function initialVoucher(): BelongsTo
    {
        return $this->belongsTo(InitialVoucher::class);
    }

    /**
     * Get the merchant vouchers for this claim.
     */
    public function merchantVouchers(): HasManyThrough
    {
        return $this->hasManyThrough(
            MerchantVoucher::class,
            InitialVoucher::class,
            'id', // Foreign key on initial_vouchers table
            'initial_voucher_id', // Foreign key on merchant_vouchers table
            'initial_voucher_id', // Local key on claims table
            'id' // Local key on initial_vouchers table
        );
    }
}
