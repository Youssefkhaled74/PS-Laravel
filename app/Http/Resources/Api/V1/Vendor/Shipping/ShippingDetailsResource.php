<?php

namespace App\Http\Resources\Api\V1\Vendor\Shipping;

use Illuminate\Http\Resources\Json\JsonResource;

class ShippingDetailsResource extends JsonResource
{
    public function toArray($request): array
    {
        $withinCity = (int) ($this->within_city_fee ?? 0);
        $withinKsa = (int) ($this->within_ksa_fee ?? 0);
        $ksaGcc = (int) ($this->ksa_to_gcc_fee ?? 0);
        $ksaWorld = (int) ($this->ksa_to_world_fee ?? 0);

        return [
            'id' => $this->id,
            'vendor_id' => $this->vendor_id,
            'within_city_fee' => $withinCity,
            'within_city_fee_formatted' => number_format($withinCity / 100, 2),
            'within_ksa_fee' => $withinKsa,
            'within_ksa_fee_formatted' => number_format($withinKsa / 100, 2),
            'ksa_to_gcc_fee' => $ksaGcc,
            'ksa_to_gcc_fee_formatted' => number_format($ksaGcc / 100, 2),
            'ksa_to_world_fee' => $ksaWorld,
            'ksa_to_world_fee_formatted' => number_format($ksaWorld / 100, 2),
            'currency' => $this->currency ?? 'SAR',
            'status' => $this->status ?? 'active',
            'updated_at' => $this->updated_at?->toDateTimeString(),
            'created_at' => $this->created_at?->toDateTimeString(),
        ];
    }
}
