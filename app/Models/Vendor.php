<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'phone_verified_at' => 'datetime',
        'email_verified_at' => 'datetime',
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

    public function activePackageAssignment(): HasOne
    {
        return $this->hasOne(VendorPackageAssignment::class, 'vendor_id')->where('status', 'active');
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
}
