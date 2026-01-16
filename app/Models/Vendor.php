<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Vendor extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'full_name',
        'name',
        'email',
        'phone',
        'second_phone',
        'whatsapp_phone',
        'bio',
        'avatar_path',
        'avatar',
        'location_text',
        'national_address',
        'national_id',
        'lat',
        'lng',
        'password',
        'status',
        'phone_verified_at',
        'onboarding_step',
        'rejection_reason',
        'otp_last_sent_at',
        'otp_locked_until',
        'otp_attempts',
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'phone_verified_at' => 'datetime',
        'email_verified_at' => 'datetime',
        'otp_last_sent_at' => 'datetime',
        'otp_locked_until' => 'datetime',
        'lat' => 'decimal:8',
        'lng' => 'decimal:8',
    ];

    public function businessProfile(): HasOne
    {
        return $this->hasOne(VendorBusinessProfile::class, 'vendor_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(VendorDocument::class, 'vendor_id');
    }

    public function vendorPackageAssignments(): HasMany
    {
        return $this->hasMany(VendorPackageAssignment::class, 'vendor_id');
    }

    public function packageAssignments(): HasMany
    {
        return $this->vendorPackageAssignments();
    }

    public function activePackageAssignment(): HasOne
    {
        return $this->hasOne(VendorPackageAssignment::class, 'vendor_id')
            ->where('status', 'active')
            ->orWhere('status', 'pending')
            ->latest();
    }

    public function paymentSelections(): HasMany
    {
        return $this->hasMany(VendorPaymentSelection::class, 'vendor_id');
    }

    public function stories(): HasMany
    {
        return $this->hasMany(VendorStory::class, 'vendor_id');
    }

    public function activeStories(): HasMany
    {
        return $this->hasMany(VendorStory::class, 'vendor_id')
            ->active()
            ->ordered();
    }

    public function brands(): BelongsToMany
    {
        return $this->belongsToMany(Brand::class, 'brand_vendor');
    }

    public function otps(): HasMany
    {
        return $this->hasMany(VendorOtp::class, 'vendor_id');
    }

    public function paymentAttempts(): HasMany
    {
        return $this->hasMany(VendorPaymentAttempt::class, 'vendor_id');
    }

    public function subscriptions(): HasMany
    {
        return $this->vendorPackageAssignments();
    }
}
