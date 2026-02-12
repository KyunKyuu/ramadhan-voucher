<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MerchantOfferImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'merchant_offer_id',
        'path',
    ];

    /**
     * Get the merchant offer that owns this image.
     */
    public function merchantOffer(): BelongsTo
    {
        return $this->belongsTo(MerchantOffer::class);
    }
}
