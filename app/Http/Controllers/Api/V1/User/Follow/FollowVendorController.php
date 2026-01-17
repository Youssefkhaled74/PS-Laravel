<?php

namespace App\Http\Controllers\Api\V1\User\Follow;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\User\Follow\FollowVendorRequest;
use App\Http\Requests\Api\V1\User\Follow\ListFollowingRequest;
use App\Http\Resources\Api\V1\User\Follow\FollowingListResource;
use App\Services\Follow\FollowService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class FollowVendorController extends Controller
{
    use ApiResponseTrait;

    protected FollowService $service;

    public function __construct(FollowService $service)
    {
        $this->service = $service;
    }

    public function follow(FollowVendorRequest $request)
    {
        $user = $request->user();
        $res = $this->service->followVendor($user, (int) $request->input('vendor_id'));
        if (! $res['ok']) {
            return $this->error('follow.errors.' . ($res['reason'] ?? 'vendor_not_found'));
        }

        return $this->success(null, 'follow.vendor_followed');
    }

    public function unfollow(Request $request, $vendorId)
    {
        $user = $request->user();
        $res = $this->service->unfollowVendor($user, (int) $vendorId);
        if (! $res['ok']) return $this->error('follow.errors.vendor_not_found');
        return $this->success(null, 'follow.vendor_unfollowed');
    }

    public function toggle(FollowVendorRequest $request)
    {
        $user = $request->user();
        $res = $this->service->toggleFollow($user, (int) $request->input('vendor_id'));
        if (! $res['ok']) return $this->error('follow.errors.vendor_not_found');
        return $this->success(['is_following' => $res['is_following'] ?? false], 'follow.vendor_follow_toggled');
    }

    public function list(ListFollowingRequest $request)
    {
        $user = $request->user();
        $paginator = $this->service->listUserFollowing($user, $request->only(['search', 'status', 'per_page']));

        $collection = FollowingListResource::collection($paginator);
        return $this->paginated($collection);
    }
}
