<?php

namespace App\Services\Admin;

use App\Models\Vendor;
use Illuminate\Support\Str;

class VendorService
{
    public function updateAccount(Vendor $vendor, array $data)
    {
        if (isset($data['avatar']) && $data['avatar']) {
            $file = $data['avatar'];
            $dir = public_path('uploads/vendors/'.$vendor->id);
            if (! file_exists($dir)) mkdir($dir, 0755, true);
            $name = 'avatar_'.time().'.'.$file->getClientOriginalExtension();
            $file->move($dir, $name);
            $data['avatar'] = 'uploads/vendors/'.$vendor->id.'/'.$name;
        } else {
            unset($data['avatar']);
        }

        $vendor->fill($data);
        if (isset($data['password']) && $data['password']) {
            $vendor->password = bcrypt($data['password']);
        }
        $vendor->save();
        return $vendor;
    }

    public function toggleStatus(Vendor $vendor)
    {
        $vendor->status = $vendor->status === 'active' ? 'inactive' : 'active';
        $vendor->save();
    }
}
