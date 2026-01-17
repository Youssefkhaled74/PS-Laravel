<?php

namespace App\Http\Resources\Api\V1\Vendor;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class VendorNotificationResource extends JsonResource
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

        $groupKey = 'older';
        if ($created->isToday()) {
            $groupKey = 'today';
        } elseif ($created->isYesterday()) {
            $groupKey = 'yesterday';
        }

        $groupLabel = '';
        if ($groupKey === 'today') $groupLabel = __('vendor.notifications.today');
        if ($groupKey === 'yesterday') $groupLabel = __('vendor.notifications.yesterday');
        if ($groupKey === 'older') $groupLabel = __('vendor.notifications.older');

        return [
            'id' => $this->id,
            'type' => $this->type,
            'icon' => $this->icon,
            'title' => $title,
            'body' => $body,
            'data' => $this->data,
            'read_at' => $this->read_at ? $this->read_at->toIso8601String() : null,
            'created_at' => $created->toIso8601String(),
            'group_key' => $groupKey,
            'group_label' => $groupLabel,
        ];
    }
}
