<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Admin;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VendorItem extends Model
{
    protected $fillable = [
        'vendor_id','category_id','piece_type_id','brand_id','gender_id','size_id','color_id',
        'name','quantity_available','quantity_per_client','weight','price','discount_price','discount_ends_at',
        'warranty','promo_title','is_taxable','status', 'rejection_reason', 'approved_by_admin_id', 'approved_at',
    ];

    protected $casts = [
        'is_taxable' => 'boolean',
        'discount_ends_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function approvedBy()
    {
        return $this->belongsTo(Admin::class, 'approved_by_admin_id');
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(VendorItemImage::class);
    }
}
