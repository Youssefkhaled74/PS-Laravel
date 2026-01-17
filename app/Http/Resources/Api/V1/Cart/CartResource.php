<?php

namespace App\Http\Resources\Api\V1\Cart;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public function toArray($request)
    {
        $items = $this->items->map(fn($i) => [
            'id' => $i->id,
            'vendor_item_id' => $i->vendor_item_id,
            'name' => $i->vendorItem?->name,
            'unit_price' => $i->unit_price,
            'quantity' => $i->quantity,
            'total_line' => $i->unit_price * $i->quantity,
        ]);

        $subtotal = $items->sum('total_line');

        return [
            'cart_id' => $this->id,
            'vendor_id' => $this->vendor_id,
            'items_count' => $items->count(),
            'items' => $items,
            'subtotal' => $subtotal,
        ];
    }
}
