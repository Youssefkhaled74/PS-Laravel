<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'full_name' => $this->full_name,
            'country_code' => $this->country_code,
            'phone' => $this->phone,
            'email' => $this->email,
            'phone_verified_at' => optional($this->phone_verified_at)->toDateTimeString(),
            'location_text' => $this->location_text ?? null,
            'lat' => $this->lat ?? null,
            'lng' => $this->lng ?? null,
            'created_at' => optional($this->created_at)->toDateTimeString(),
            'updated_at' => optional($this->updated_at)->toDateTimeString(),
        ];
    }
}