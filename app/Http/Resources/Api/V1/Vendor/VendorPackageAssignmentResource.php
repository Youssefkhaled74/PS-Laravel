<?php

namespace App\Http\Resources\Api\V1\Vendor;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VendorPackageAssignmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'vendor_id' => $this->vendor_id,
            'billing_cycle' => $this->billing_cycle,
            'price' => $this->price,
            'price_formatted' => $this->formatPrice($this->price),
            'currency' => $this->currency,
            'status' => $this->status,
            'starts_at' => $this->starts_at?->toIso8601String(),
            'ends_at' => $this->ends_at?->toIso8601String(),
            
            // Package details
            'package' => $this->whenLoaded('package', function () {
                return new VendorPackageResource($this->package);
            }),

            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
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
