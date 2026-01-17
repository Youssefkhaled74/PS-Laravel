<?php

namespace App\Http\Resources\Api\V1\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserMiniResource extends JsonResource
{
    public function toArray($request)
    {
        $avatar = $this->avatar_path ?? $this->avatar ?? null;

        return [
            'id' => $this->id,
            'name' => $this->full_name ?? $this->name,
            'phone' => $this->phone,
            'avatar_url' => $avatar ? asset($avatar) : null,
        ];
    }
}
