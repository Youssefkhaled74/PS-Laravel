<?php

namespace App\Services\Vendor\Packages;

use App\Models\VendorPackage;
use Illuminate\Database\Eloquent\Collection;

class PackageService
{
    /**
     * Get all active packages ordered by sort_order
     */
    public function getActivePackages(): Collection
    {
        return VendorPackage::where('status', VendorPackage::STATUS_ACTIVE)
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * Get package by key
     */
    public function getPackageByKey(string $key): ?VendorPackage
    {
        return VendorPackage::where('key', $key)
            ->where('status', VendorPackage::STATUS_ACTIVE)
            ->first();
    }

    /**
     * Get package by ID
     */
    public function getPackageById(int $id): ?VendorPackage
    {
        return VendorPackage::where('id', $id)
            ->where('status', VendorPackage::STATUS_ACTIVE)
            ->first();
    }
}
