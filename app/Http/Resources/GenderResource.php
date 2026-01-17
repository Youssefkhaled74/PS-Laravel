<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GenderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'key' => $this->key ?? null,
            'name_en' => $this->name_en,
            'name_ar' => $this->name_ar,
        ];
    }
}
