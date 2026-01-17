<?php

namespace App\Services\Vendor\Notifications;

use App\Models\VendorNotification;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class VendorNotificationService
{
    public function list($vendor, array $filters = []): LengthAwarePaginator
    {
        $perPage = isset($filters['per_page']) ? (int)$filters['per_page'] : 20;
        $perPage = max(1, min(50, $perPage));

        $query = VendorNotification::where('vendor_id', $vendor->id);

        if (!empty($filters['unread_only'])) {
            $query->whereNull('read_at');
        }

        $query->orderBy('created_at', 'desc');

        return $query->paginate($perPage);
    }

    public function unreadCount($vendor): int
    {
        return VendorNotification::where('vendor_id', $vendor->id)
            ->whereNull('read_at')
            ->count();
    }
}
