<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'full_name',
        'country_code',
        'phone',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'phone_verified_at' => 'datetime',
    ];

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function storyViews(): HasMany
    {
        return $this->hasMany(VendorStoryView::class);
    }
    
    public function followedVendors(): BelongsToMany
    {
        return $this->belongsToMany(Vendor::class, 'vendor_follows')
            ->withTimestamps()
            ->withPivot(['status']);
    }

    public function isFollowing($vendorId): bool
    {
        return $this->followedVendors()->where('vendor_id', $vendorId)->exists();
    }
}
