<?php

namespace App\Http\Resources\Api\V1\Cart;

use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'vendor_item_id' => $this->vendor_item_id,
            'name' => $this->vendorItem?->name,
            'unit_price' => $this->unit_price,
            'quantity' => $this->quantity,
            'total_line' => $this->unit_price * $this->quantity,
        ];
    }
}
