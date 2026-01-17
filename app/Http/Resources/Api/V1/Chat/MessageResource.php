<?php

namespace App\Http\Resources\Api\V1\Chat;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    public function toArray($request)
    {
        $actor = $request->user() ?? $request->user('vendor');
        $actorType = $actor && $actor instanceof \App\Models\Vendor ? 'vendor' : 'user';

        return [
            'id' => $this->id,
            'body' => $this->body,
            'message_type' => $this->message_type,
            'attachment_url' => $this->attachment_path ? asset($this->attachment_path) : null,
            'sender_type' => $this->sender_type,
            'created_at' => $this->created_at->toDateTimeString(),
            'is_mine' => ($this->sender_type === $actorType && $this->sender_id === ($actor->id ?? null)),
        ];
    }
}
