<?php

namespace App\Http\Controllers\Api\V1\Vendor\Notifications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Vendor\Notifications\VendorNotificationService;
use App\Http\Resources\Api\V1\Vendor\VendorNotificationResource;
use App\Traits\ApiResponseTrait;

class NotificationController extends Controller
{
    use ApiResponseTrait;

    protected VendorNotificationService $service;

    public function __construct(VendorNotificationService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $vendor = $request->user();

        $perPage = (int)$request->query('per_page', 20);
        $perPage = max(1, min(50, $perPage));
        $unreadOnly = (int)$request->query('unread_only', 0) === 1;

        $paginator = $this->service->list($vendor, [
            'per_page' => $perPage,
            'unread_only' => $unreadOnly,
        ]);

        $unreadCount = $this->service->unreadCount($vendor);

        $collection = VendorNotificationResource::collection($paginator);
        $resourceArray = $collection->response()->getData(true);

        $data = [
            'items' => $resourceArray['data'] ?? [],
            'meta' => $resourceArray['meta'] ?? [],
            'links' => $resourceArray['links'] ?? [],
            'unread_count' => $unreadCount,
        ];

        return $this->success($data, 'vendor.notifications.list_success');
    }
}
