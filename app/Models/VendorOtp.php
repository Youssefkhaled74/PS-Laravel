<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorOtp extends Model
{
    protected $fillable = [
        'vendor_id',
        'phone',
        'otp_hash',
        'expires_at',
        'consumed_at',
        'resend_available_at',
        'attempts',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'consumed_at' => 'datetime',
        'resend_available_at' => 'datetime',
    ];

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * Check if OTP is expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Check if OTP is consumed
     */
    public function isConsumed(): bool
    {
        return $this->consumed_at !== null;
    }

    /**
     * Check if resend is available
     */
    public function canResend(): bool
    {
        return $this->resend_available_at->isPast();
    }

    /**
     * Get seconds until resend available
     */
    public function getResendInSeconds(): int
    {
        if ($this->canResend()) {
            return 0;
        }
        return max(0, $this->resend_available_at->diffInSeconds(now()));
    }

    /**
     * Get seconds until OTP expires
     */
    public function getExpiresInSeconds(): int
    {
        if ($this->isExpired()) {
            return 0;
        }
        return max(0, $this->expires_at->diffInSeconds(now()));
    }
}
