<?php

namespace App\Http\Resources\Api\V1\User\Notifications;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class UserNotificationResource extends JsonResource
{
    public function toArray($request)
    {
        $lang = $request->header('Lang') ?? app()->getLocale();
        $lang = strtolower(substr($lang, 0, 2));

        $title = '';
        $body = '';
        if (is_array($this->title)) {
            $title = $this->title[$lang] ?? $this->title['en'] ?? '';
        }
        if (is_array($this->body)) {
            $body = $this->body[$lang] ?? $this->body['en'] ?? '';
        }

        $created = $this->created_at instanceof Carbon ? $this->created_at : Carbon::parse($this->created_at);

        $groupKey = 'earlier';
        if ($created->isToday()) $groupKey = 'today';
        elseif ($created->isYesterday()) $groupKey = 'yesterday';

        return [
            'id' => $this->id,
            'type' => $this->type,
            'icon_key' => $this->icon,
            'title' => $title,
            'body' => $body,
            'data' => $this->data,
            'is_read' => $this->read_at ? true : false,
            'created_at' => $created->toIso8601String(),
            'created_at_human' => $created->diffForHumans(),
            'group_key' => $groupKey,
        ];
    }
}
