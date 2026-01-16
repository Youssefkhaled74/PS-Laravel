<?php

namespace App\Http\Resources\Api\V1\Vendor;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VendorPackageResource extends JsonResource
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
            'monthly_price' => $this->monthly_price,
            'monthly_price_formatted' => $this->formatPrice($this->monthly_price),
            'yearly_price' => $this->yearly_price,
            'yearly_price_formatted' => $this->formatPrice($this->yearly_price),
            'currency' => $this->currency ?? 'SAR',
            'features' => $this->getFeatures($locale),
            'status' => $this->status,
            'sort_order' => $this->sort_order,
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }

    /**
     * Format price from cents to SAR
     */
    protected function formatPrice(int $priceInCents): string
    {
        $price = $priceInCents / 100;
        return number_format($price, 2);
    }
}
