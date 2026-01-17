<?php

namespace App\Http\Resources\Api\V1\Chat;

use Illuminate\Http\Resources\Json\JsonResource;

class ConversationResource extends JsonResource
{
    public function toArray($request)
    {
        $actor = $request->user() ?? $request->user('vendor');
        $actorType = $actor && $actor instanceof \App\Models\Vendor ? 'vendor' : 'user';

        $other = null;
        if ($actorType === 'user') {
            $other = $this->vendor;
            $unread = $this->user_unread_count;
        } else {
            $other = $this->user;
            $unread = $this->vendor_unread_count;
        }

        $last = $this->latestMessage;
        $preview = null;
        if ($last) {
            $preview = $last->message_type === 'text' ? (strlen($last->body) > 100 ? substr($last->body, 0, 100) . '...' : $last->body) : __('api.chat.last_message_attachment');
        }

        return [
            'conversation_id' => $this->id,
            'other_party' => [
                'id' => $other->id ?? null,
                'name' => $other->name_en ?? $other->full_name ?? null,
                'avatar' => isset($other->avatar) ? asset($other->avatar) : null,
            ],
            'last_message_preview' => $preview,
            'last_message_at' => $this->last_message_at ? $this->last_message_at->toDateTimeString() : null,
            'unread_count' => $unread,
        ];
    }
}
