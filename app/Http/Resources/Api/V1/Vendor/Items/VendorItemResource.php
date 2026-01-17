<?php

namespace App\Http\Resources\Api\V1\Vendor\Items;

use Illuminate\Http\Resources\Json\JsonResource;

class VendorItemResource extends JsonResource
{
    public function toArray($request)
    {
        $images = collect($this->images)->map(function ($img) {
            return [
                'id' => $img->id,
                'url' => $img->path ? asset($img->path) : null,
                'sort_order' => $img->sort_order,
            ];
        })->values();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'discount_price' => $this->discount_price,
            'discount_ends_at' => $this->discount_ends_at,
            'quantity_available' => $this->quantity_available,
            'quantity_per_client' => $this->quantity_per_client,
            'weight' => $this->weight,
            'warranty' => $this->warranty,
            'promo_title' => $this->promo_title,
            'is_taxable' => (bool)$this->is_taxable,
            'status' => $this->status,
            'images' => $images,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
