<?php

namespace App\Services\Vendor\Shipping;

use App\Models\Vendor;
use App\Models\VendorShippingDetail;
use Illuminate\Support\Facades\DB;

class ShippingDetailsService
{
    public function getForVendor(Vendor $vendor): ?VendorShippingDetail
    {
        return VendorShippingDetail::where('vendor_id', $vendor->id)->first();
    }

    protected function toCents($value): int
    {
        if (! is_numeric($value)) return 0;
        return intval(round($value * 100));
    }

    public function upsertForVendor(Vendor $vendor, array $data): VendorShippingDetail
    {
        return DB::transaction(function () use ($vendor, $data) {
            $payload = [
                'within_city_fee' => $this->toCents($data['within_city_fee'] ?? 0),
                'within_ksa_fee' => $this->toCents($data['within_ksa_fee'] ?? 0),
                'ksa_to_gcc_fee' => $this->toCents($data['ksa_to_gcc_fee'] ?? 0),
                'ksa_to_world_fee' => $this->toCents($data['ksa_to_world_fee'] ?? 0),
                'currency' => $data['currency'] ?? 'SAR',
                'status' => $data['status'] ?? 'active',
            ];

            $model = VendorShippingDetail::updateOrCreate(
                ['vendor_id' => $vendor->id],
                array_merge(['vendor_id' => $vendor->id], $payload)
            );

            return $model->refresh();
        });
    }
}
