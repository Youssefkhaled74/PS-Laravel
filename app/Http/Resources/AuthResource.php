<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'token' => $this->resource['token'] ?? null,
            'token_type' => $this->resource['token_type'] ?? 'Bearer',
            'user' => new UserResource($this->resource['user'] ?? $this->resource),
        ];
    }
}
