<?php

namespace App\Http\Resources\Api\V1\Vendor;

use Illuminate\Http\Resources\Json\JsonResource;

class VendorResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'second_phone' => $this->second_phone,
            'avatar_url' => $this->avatar_path ? asset($this->avatar_path) : null,
            'bio' => $this->bio,
            'national_id' => $this->national_id,
            'national_address' => $this->national_address,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'status' => $this->status,
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
