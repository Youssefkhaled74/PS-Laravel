<?php

namespace App\Http\Resources\Api\V1\User;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'vendor_id' => $this->vendor_id,
            'subtotal' => $this->subtotal,
            'shipping_fee' => $this->shipping_fee,
            'vat' => $this->vat,
            'total' => $this->total,
            'status' => $this->status,
            'items' => $this->items->map(fn($i) => [
                'id' => $i->id,
                'vendor_item_id' => $i->vendor_item_id,
                'quantity' => $i->quantity,
                'unit_price' => $i->unit_price,
                'line_total' => $i->line_total,
            ]),
            'created_at' => $this->created_at,
        ];
    }
}
