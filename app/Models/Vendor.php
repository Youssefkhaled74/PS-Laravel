<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vendor extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name','email','phone','whatsapp_phone','bio','avatar','location_text','national_id','password','status',
    ];

    protected $hidden = ['password'];

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
}
