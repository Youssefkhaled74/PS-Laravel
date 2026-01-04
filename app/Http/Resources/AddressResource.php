<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'label' => $this->label,
            'country' => $this->country,
            'city' => $this->city,
            'district' => $this->district,
            'street' => $this->street,
            'building_no' => $this->building_no,
            'apartment_no' => $this->apartment_no,
            'floor' => $this->floor,
            'postal_code' => $this->postal_code,
            'phone' => $this->phone,
            'notes' => $this->notes,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'is_default' => (bool) $this->is_default,
            'created_at' => optional($this->created_at)->toDateTimeString(),
            'updated_at' => optional($this->updated_at)->toDateTimeString(),
        ];
    }
}
