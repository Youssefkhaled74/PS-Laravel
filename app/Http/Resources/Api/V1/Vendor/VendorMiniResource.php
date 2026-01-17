<?php

namespace App\Http\Resources\Api\V1\Vendor;

use Illuminate\Http\Resources\Json\JsonResource;

class VendorMiniResource extends JsonResource
{
    public function toArray($request)
    {
        $avatar = $this->avatar_path ?? $this->avatar ?? null;

        return [
            'id' => $this->id,
            'name' => $this->name ?? $this->full_name,
            'avatar_url' => $avatar ? asset($avatar) : null,
            'status' => $this->status,
            'follower_count' => $this->when(isset($this->followers_count), $this->followers_count),
            'is_followed' => $this->when(isset($request->user()), (bool) optional($request->user())->isFollowing($this->id)),
        ];
    }
}
