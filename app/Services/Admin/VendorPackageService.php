<?php

namespace App\Services\Admin;

use App\Models\VendorPackage;
use Illuminate\Support\Arr;

class VendorPackageService
{
    public function paginate(array $filters = [], int $perPage = 10)
    {
        $q = VendorPackage::query();

        if (!empty($filters['search'])) {
            $s = $filters['search'];
            $q->where(function ($q2) use ($s) {
                $q2->where('name->en', 'like', "%{$s}%")
                   ->orWhere('name->ar', 'like', "%{$s}%");
            });
        }

        if (!empty($filters['status']) && in_array($filters['status'], [VendorPackage::STATUS_ACTIVE, VendorPackage::STATUS_INACTIVE])) {
            $q->where('status', $filters['status']);
        }

        $q->orderBy('sort_order', 'asc')->orderBy('id', 'desc');

        return $q->paginate($perPage)->withQueryString();
    }

    public function create(array $data): VendorPackage
    {
        $data['name'] = Arr::get($data, 'name');
        return VendorPackage::create($data);
    }

    public function update(VendorPackage $pkg, array $data): VendorPackage
    {
        $pkg->update($data);
        return $pkg;
    }

    public function toggle(VendorPackage $pkg): VendorPackage
    {
        $pkg->status = $pkg->status === VendorPackage::STATUS_ACTIVE ? VendorPackage::STATUS_INACTIVE : VendorPackage::STATUS_ACTIVE;
        $pkg->save();
        return $pkg;
    }

    public function delete(VendorPackage $pkg): void
    {
        $pkg->delete();
    }
}
