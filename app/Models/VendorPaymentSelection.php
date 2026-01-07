<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorPaymentSelection extends Model
{
    use HasFactory;

    protected $fillable = ['vendor_id','vendor_package_assignment_id','payment_method','status','meta'];

    protected $casts = ['meta' => 'array'];

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(VendorPackageAssignment::class, 'vendor_package_assignment_id');
    }
}
