<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\VendorStoryVendorListResource;
use App\Http\Resources\VendorStoryItemResource;
use App\Models\Vendor;
use App\Models\VendorStory;
use App\Services\VendorStoryService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserStoriesController extends Controller
{
    use ApiResponseTrait;

    protected VendorStoryService $storyService;

    public function __construct(VendorStoryService $storyService)
    {
        $this->storyService = $storyService;
    }

    /**
     * Get list of vendors with active stories
     * 
     * @return JsonResponse
     */
    public function getVendors(Request $request): JsonResponse
    {
        $userId = $request->user()?->id;
        
        $vendors = $this->storyService->getVendorsWithStories($userId);

        return $this->success(
            VendorStoryVendorListResource::collection($vendors),
            'success'
        );
    }

    /**
     * Get stories for a specific vendor
     * 
     * @param Vendor $vendor
     * @return JsonResponse
     */
    public function getVendorStories(Request $request, Vendor $vendor): JsonResponse
    {
        if ($vendor->status !== 'active') {
            return $this->error('not_found', null, 404);
        }

        $userId = $request->user()?->id;
        $stories = $this->storyService->getVendorStories($vendor, $userId);

        if ($stories->isEmpty()) {
            return $this->error('not_found', null, 404);
        }

        $nextVendor = $this->storyService->getNextVendor($vendor, $userId);

        return $this->success([
            'vendor' => [
                'id' => $vendor->id,
                'name' => $vendor->name,
                'logo_url' => $vendor->avatar ? asset($vendor->avatar) : null,
                'country_code' => $vendor->businessProfile->country_code ?? null,
            ],
            'stories' => VendorStoryItemResource::collection($stories),
            'next_vendor_id' => $nextVendor?->id,
        ], 'success');
    }

    /**
     * Mark story as viewed
     * 
     * @param VendorStory $story
     * @return JsonResponse
     */
    public function markStoryAsViewed(Request $request, VendorStory $story): JsonResponse
    {
        $userId = $request->user()->id;

        if (!$story->isActive()) {
            return $this->error('not_found', null, 404);
        }

        $this->storyService->markStoryAsViewed($story, $userId);
        
        $vendorHasUnread = $this->storyService->vendorHasUnread($story->vendor, $userId);

        return $this->success([
            'vendor_has_unread' => $vendorHasUnread,
        ], 'success');
    }

    /**
     * Mark all vendor stories as viewed
     * 
     * @param Vendor $vendor
     * @return JsonResponse
     */
    public function markVendorStoriesAsViewed(Request $request, Vendor $vendor): JsonResponse
    {
        $userId = $request->user()->id;

        if ($vendor->status !== 'active') {
            return $this->error('not_found', null, 404);
        }

        $count = $this->storyService->markVendorStoriesAsViewed($vendor, $userId);

        return $this->success([
            'marked_count' => $count,
        ], 'success');
    }
}
