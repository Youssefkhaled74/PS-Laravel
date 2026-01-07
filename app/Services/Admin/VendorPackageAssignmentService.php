<?php

namespace App\Services\Admin;

use App\Models\VendorPackage;
use App\Models\VendorPackageAssignment;
use App\Models\Vendor;
use Carbon\Carbon;

class VendorPackageAssignmentService
{
    public function assignToVendor(Vendor $vendor, VendorPackage $package, string $billingCycle, ?\Carbon\Carbon $startsAt = null, ?\App\Models\Admin $admin = null): VendorPackageAssignment
    {
        $startsAt = $startsAt ?? Carbon::now();

        // Cancel previous active assignment
        $prev = VendorPackageAssignment::where('vendor_id', $vendor->id)->where('status', 'active')->first();
        if ($prev) {
            $prev->status = 'canceled';
            $prev->ends_at = $startsAt;
            $prev->save();
        }

        $price = $billingCycle === 'yearly' ? $package->yearly_price : $package->monthly_price;

        $endsAt = null;
        if ($billingCycle === 'monthly') {
            $endsAt = (clone $startsAt)->addMonth();
        } else {
            $endsAt = (clone $startsAt)->addYear();
        }

        $assign = VendorPackageAssignment::create([
            'vendor_id' => $vendor->id,
            'vendor_package_id' => $package->id,
            'billing_cycle' => $billingCycle,
            'price' => $price,
            'currency' => $package->currency,
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'status' => 'active',
            'assigned_by_admin_id' => $admin?->id,
        ]);

        return $assign;
    }
}
