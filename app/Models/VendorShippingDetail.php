<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorShippingDetail extends Model
{
    protected $table = 'vendor_shipping_details';

    protected $fillable = [
        'vendor_id',
        'within_city_fee',
        'within_ksa_fee',
        'ksa_to_gcc_fee',
        'ksa_to_world_fee',
        'currency',
        'status',
    ];

    protected $casts = [
        'within_city_fee' => 'integer',
        'within_ksa_fee' => 'integer',
        'ksa_to_gcc_fee' => 'integer',
        'ksa_to_world_fee' => 'integer',
    ];

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
}
