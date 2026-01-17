<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VendorItem extends Model
{
    protected $fillable = [
        'vendor_id','category_id','piece_type_id','brand_id','gender_id','size_id','color_id',
        'name','quantity_available','quantity_per_client','weight','price','discount_price','discount_ends_at',
        'warranty','promo_title','is_taxable','status',
    ];

    protected $casts = [
        'is_taxable' => 'boolean',
        'discount_ends_at' => 'datetime',
    ];

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(VendorItemImage::class);
    }
}
