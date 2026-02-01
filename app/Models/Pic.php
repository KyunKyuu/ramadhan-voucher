<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pic extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the initial vouchers assigned to this PIC.
     */
    public function initialVouchers(): HasMany
    {
        return $this->hasMany(InitialVoucher::class, 'assigned_pic_id');
    }
}
