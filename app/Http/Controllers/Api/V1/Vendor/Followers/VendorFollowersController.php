<?php

namespace App\Http\Controllers\Api\V1\Vendor\Followers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Vendor\Followers\ListFollowersRequest;
use App\Http\Resources\Api\V1\Vendor\Followers\FollowerListResource;
use App\Services\Follow\FollowService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class VendorFollowersController extends Controller
{
    use ApiResponseTrait;

    protected FollowService $service;

    public function __construct(FollowService $service)
    {
        $this->service = $service;
    }

    public function list(ListFollowersRequest $request)
    {
        $vendor = $request->user();
        $paginator = $this->service->listVendorFollowers($vendor, $request->only(['search', 'per_page']));

        $collection = FollowerListResource::collection($paginator);
        return $this->paginated($collection);
    }

    public function count(Request $request)
    {
        $vendor = $request->user();
        return $this->success(['followers_count' => $vendor->followers()->count()], 'follow.list_followers');
    }
}
