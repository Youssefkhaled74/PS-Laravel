<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VendorStoryItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'media_type' => $this->media_type,
            'media_url' => $this->media_url,
            'thumb_url' => $this->thumb_url,
            'duration_seconds' => $this->duration_seconds ?? ($this->media_type === 'image' ? 5 : null),
            'is_viewed' => $this->is_viewed ?? false,
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
