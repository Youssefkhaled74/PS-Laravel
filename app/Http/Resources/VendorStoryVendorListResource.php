<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VendorStoryVendorListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'vendor_id' => $this['vendor']->id,
            'vendor_name' => $this['vendor']->name,
            'vendor_logo_url' => $this['vendor']->avatar ? asset($this['vendor']->avatar) : null,
            'country_code' => $this['vendor']->businessProfile->country_code ?? null,
            'has_unread' => $this['has_unread'],
            'stories_count' => $this['stories_count'],
            'latest_story_created_at' => $this['latest_story_created_at']?->toIso8601String(),
        ];
    }
}
