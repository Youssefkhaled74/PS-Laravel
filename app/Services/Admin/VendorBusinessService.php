<?php

namespace App\Services\Admin;

use App\Models\Vendor;

class VendorBusinessService
{
    public function updateBusiness(Vendor $vendor, array $data)
    {
        $profile = $vendor->businessProfile ?? $vendor->businessProfile()->create([]);
        $profile->fill($data);
        $profile->save();
        return $profile;
    }
}
