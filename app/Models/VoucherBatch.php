<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VoucherBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'generated_count',
        'created_by_admin_id',
    ];

    protected $casts = [
        'generated_count' => 'integer',
    ];

    /**
     * Get the admin who created this batch.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_admin_id');
    }

    /**
     * Get the initial vouchers in this batch.
     */
    public function initialVouchers(): HasMany
    {
        return $this->hasMany(InitialVoucher::class, 'batch_id');
    }
}
