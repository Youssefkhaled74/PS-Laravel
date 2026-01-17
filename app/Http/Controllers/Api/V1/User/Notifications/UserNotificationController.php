<?php

namespace App\Http\Controllers\Api\V1\User\Notifications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\User\Notifications\UserNotificationService;
use App\Http\Resources\Api\V1\User\Notifications\UserNotificationResource;
use App\Traits\ApiResponseTrait;

class UserNotificationController extends Controller
{
    use ApiResponseTrait;

    protected UserNotificationService $service;

    public function __construct(UserNotificationService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $user = $request->user();

        $perPage = (int)$request->query('per_page', 20);
        $perPage = max(1, min(50, $perPage));
        $onlyUnread = (int)$request->query('only_unread', 0) === 1;

        $paginator = $this->service->list($user, [
            'per_page' => $perPage,
            'only_unread' => $onlyUnread,
        ]);

        $unreadCount = $this->service->unreadCount($user);

        $collection = UserNotificationResource::collection($paginator);
        $resourceArray = $collection->response()->getData(true);

        // Group items into sections
        $items = $resourceArray['data'] ?? [];
        $sections = [
            ['key' => 'today', 'title' => __('notifications.sections.today'), 'items' => []],
            ['key' => 'yesterday', 'title' => __('notifications.sections.yesterday'), 'items' => []],
            ['key' => 'earlier', 'title' => __('notifications.sections.earlier'), 'items' => []],
        ];

        foreach ($items as $it) {
            if (($it['group_key'] ?? '') === 'today') $sections[0]['items'][] = $it;
            elseif (($it['group_key'] ?? '') === 'yesterday') $sections[1]['items'][] = $it;
            else $sections[2]['items'][] = $it;
        }

        $data = [
            'sections' => $sections,
            'unread_count' => $unreadCount,
            'meta' => $resourceArray['meta'] ?? null,
            'links' => $resourceArray['links'] ?? null,
        ];

        return $this->success($data, 'notifications.fetched');
    }

    public function markAsRead(Request $request, $id)
    {
        $user = $request->user();

        try {
            $n = $this->service->markAsRead($user, $id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->error('notifications.errors.not_found', null, 404);
        }

        $unreadCount = $this->service->unreadCount($user);

        return $this->success(new UserNotificationResource($n), 'notifications.mark_read', ['unread_count' => $unreadCount]);
    }

    public function markAllAsRead(Request $request)
    {
        $user = $request->user();

        $this->service->markAllAsRead($user);
        return $this->success(['unread_count' => 0], 'notifications.read_all');
    }
}
