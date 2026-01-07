<?php

namespace App\Services;

use App\Models\Vendor;
use App\Models\VendorBusinessProfile;
use App\Models\VendorDocument;
use App\Models\VendorPackage;
use App\Models\VendorPackageAssignment;
use App\Models\VendorPaymentSelection;
use App\Services\UploadService;
use Carbon\Carbon;

class VendorOnboardingService
{
    public function __construct(protected UploadService $uploader)
    {
    }

    public function registerVendor(array $data): Vendor
    {
        $data['password'] = bcrypt($data['password']);
        return Vendor::create($data);
    }

    public function updateBusinessProfile(Vendor $vendor, array $data): VendorBusinessProfile
    {
        $profile = $vendor->businessProfile ?: new VendorBusinessProfile(['vendor_id' => $vendor->id]);
        $profile->fill($data);
        $profile->save();
        return $profile;
    }

    public function uploadDocument(Vendor $vendor, string $type, $file): VendorDocument
    {
        $path = $this->uploader->uploadPublicImage($file, "uploads/vendors/{$vendor->id}", $type);
        return VendorDocument::create(['vendor_id' => $vendor->id, 'type' => $type, 'file_path' => $path]);
    }

    public function choosePackage(Vendor $vendor, VendorPackage $package, string $billingCycle, ?Carbon $startsAt = null, ?\App\Models\Admin $admin = null): VendorPackageAssignment
    {
        $startsAt = $startsAt ?: Carbon::now();

        // cancel previous
        $prev = VendorPackageAssignment::where('vendor_id', $vendor->id)->where('status', 'active')->first();
        if ($prev) {
            $prev->status = 'canceled';
            $prev->ends_at = $startsAt;
            $prev->save();
        }

        $price = $billingCycle === 'yearly' ? $package->yearly_price : $package->monthly_price;
        $endsAt = $billingCycle === 'yearly' ? (clone $startsAt)->addYear() : (clone $startsAt)->addMonth();

        return VendorPackageAssignment::create([
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
    }

    public function choosePaymentMethod(Vendor $vendor, array $data): VendorPaymentSelection
    {
        return VendorPaymentSelection::create([
            'vendor_id' => $vendor->id,
            'vendor_package_assignment_id' => $data['vendor_package_assignment_id'] ?? null,
            'payment_method' => $data['payment_method'],
            'status' => $data['status'] ?? 'selected',
            'meta' => $data['meta'] ?? null,
        ]);
    }
}
