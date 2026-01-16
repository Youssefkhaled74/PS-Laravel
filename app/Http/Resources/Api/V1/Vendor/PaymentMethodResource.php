<?php

namespace App\Http\Resources\Api\V1\Vendor;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentMethodResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $locale = app()->getLocale();

        return [
            'id' => $this->id,
            'key' => $this->key,
            'name' => $this->getName($locale),
            'status' => $this->status,
            'sort_order' => $this->sort_order,
        ];
    }
}
