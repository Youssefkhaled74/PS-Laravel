<?php

namespace App\Services\Shipping;

use App\Models\Vendor;
use App\Models\Address;

class ShippingService
{
    /**
     * Resolve shipping fee (in cents) based on vendor's shipping detail and user address
     */
    public function resolveShippingFee(Vendor $vendor, ?Address $address): int
    {
        $detail = $vendor->shippingDetail;
        if (! $detail) return 0;

        // If both lat/lng present use distance heuristic for "within city"
        if ($address && $address->lat && $address->lng && $vendor->lat && $vendor->lng) {
            $dist = $this->haversineDistance((float)$vendor->lat, (float)$vendor->lng, (float)$address->lat, (float)$address->lng);
            if ($dist <= 30.0) {
                return (int) $detail->within_city_fee;
            }
        }

        // country-based fallback
        $country = strtolower($address->country ?? 'sa');
        if (in_array($country, ['sa', 'ksa', 'saudi arabia'])) {
            return (int) $detail->within_ksa_fee;
        }

        // GCC countries
        $gcc = ['ae','kw','om','qa','bh'];
        if (in_array($country, $gcc)) {
            return (int) $detail->ksa_to_gcc_fee;
        }

        return (int) $detail->ksa_to_world_fee;
    }

    protected function haversineDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371; // km
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        return $earthRadius * $c;
    }
}
