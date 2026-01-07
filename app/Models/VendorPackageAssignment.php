<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorPackageAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id', 'vendor_package_id', 'billing_cycle', 'price', 'currency', 'starts_at', 'ends_at', 'status', 'assigned_by_admin_id',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'price' => 'integer',
    ];

    public function package(): BelongsTo
    {
        return $this->belongsTo(VendorPackage::class, 'vendor_package_id');
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'assigned_by_admin_id');
    }
}
