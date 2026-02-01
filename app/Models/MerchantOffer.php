<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MerchantOffer extends Model
{
    use HasFactory;

    protected $fillable = [
        'merchant_id',
        'title',
        'discount_type',
        'discount_value',
        'description',
        'is_active',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the merchant that owns this offer.
     */
    public function merchant(): BelongsTo
    {
        return $this->belongsTo(Merchant::class);
    }

    /**
     * Get formatted discount text.
     */
    public function getFormattedDiscountAttribute(): string
    {
        if ($this->discount_type === 'PERCENT') {
            return $this->discount_value . '%';
        }
        return 'Rp ' . number_format($this->discount_value, 0, ',', '.');
    }
}
