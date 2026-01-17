<?php

namespace App\Services\Follow;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class FollowService
{
    public function followVendor(User $user, int $vendorId): array
    {
        $vendor = Vendor::find($vendorId);
        if (! $vendor) return ['ok' => false, 'reason' => 'vendor_not_found'];
        if ($user->id === $vendorId) return ['ok' => false, 'reason' => 'cannot_follow_self'];

        $user->followedVendors()->syncWithoutDetaching([
            $vendorId => ['status' => 'active']
        ]);

        return ['ok' => true];
    }

    public function unfollowVendor(User $user, int $vendorId): array
    {
        $vendor = Vendor::find($vendorId);
        if (! $vendor) return ['ok' => false, 'reason' => 'vendor_not_found'];

        $user->followedVendors()->detach($vendorId);

        return ['ok' => true];
    }

    public function toggleFollow(User $user, int $vendorId): array
    {
        if ($user->isFollowing($vendorId)) {
            $this->unfollowVendor($user, $vendorId);
            return ['ok' => true, 'is_following' => false];
        }

        $this->followVendor($user, $vendorId);
        return ['ok' => true, 'is_following' => true];
    }

    public function listUserFollowing(User $user, array $filters = []): LengthAwarePaginator
    {
        $perPage = $filters['per_page'] ?? 15;

        $query = $user->followedVendors()->newQuery()->select('vendors.*');

        if (! empty($filters['search'])) {
            $q = $filters['search'];
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('full_name', 'like', "%{$q}%");
            });
        }

        if (! empty($filters['status'])) {
            $query->wherePivot('status', $filters['status']);
        }

        return $query->withCount('followers')->paginate($perPage);
    }

    public function listVendorFollowers(Vendor $vendor, array $filters = []): LengthAwarePaginator
    {
        $perPage = $filters['per_page'] ?? 15;

        $query = $vendor->followers()->newQuery()->select('users.*');

        if (! empty($filters['search'])) {
            $q = $filters['search'];
            $query->where(function ($sub) use ($q) {
                $sub->where('full_name', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%");
            });
        }

        return $query->paginate($perPage);
    }

    public function getCounts(User $user): array
    {
        return [
            'following_count' => $user->followedVendors()->count(),
        ];
    }
}
