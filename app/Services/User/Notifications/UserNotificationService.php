<?php

namespace App\Services\User\Notifications;

use App\Models\UserNotification;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class UserNotificationService
{
    public function list($user, array $filters = []): LengthAwarePaginator
    {
        $perPage = isset($filters['per_page']) ? (int)$filters['per_page'] : 20;
        $perPage = max(1, min(50, $perPage));

        $query = UserNotification::where('user_id', $user->id);

        if (!empty($filters['only_unread'])) {
            $query->whereNull('read_at');
        }

        $query->orderBy('created_at', 'desc');

        return $query->paginate($perPage);
    }

    public function unreadCount($user): int
    {
        return UserNotification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->count();
    }

    public function markAsRead($user, $id)
    {
        return DB::transaction(function () use ($user, $id) {
            $n = UserNotification::where('user_id', $user->id)->where('id', $id)->firstOrFail();
            if (! $n->read_at) {
                $n->read_at = now();
                $n->save();
            }
            return $n->refresh();
        });
    }

    public function markAllAsRead($user): int
    {
        return DB::transaction(function () use ($user) {
            $updated = UserNotification::where('user_id', $user->id)->whereNull('read_at')->update(['read_at' => now()]);
            return $updated;
        });
    }
}
