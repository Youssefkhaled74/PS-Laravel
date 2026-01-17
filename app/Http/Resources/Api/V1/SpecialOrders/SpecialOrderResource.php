<?php

namespace App\Http\Resources\Api\V1\SpecialOrders;

use Illuminate\Http\Resources\Json\JsonResource;

class SpecialOrderResource extends JsonResource
{
    public function toArray($request)
    {
        $imageUrl = null;
        if (! empty($this->image_path)) {
            $imageUrl = asset($this->image_path);
        }

        return [
            'id' => $this->id,
            'status' => $this->status,
            'vendor' => [
                'id' => $this->vendor?->id,
                'name' => $this->vendor?->name,
                'avatar' => $this->vendor?->avatar_path ? asset($this->vendor->avatar_path) : null,
            ],
            'category' => $this->category?->name_en ?? null,
            'piece_type' => $this->pieceType?->name_en ?? null,
            'details' => $this->details,
            'urgent' => (bool)$this->urgent,
            'image_url' => $imageUrl,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
