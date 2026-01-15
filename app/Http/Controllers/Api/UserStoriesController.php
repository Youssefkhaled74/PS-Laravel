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

        return $this->successResponse(
            VendorStoryVendorListResource::collection($vendors),
            __('Vendors retrieved successfully.')
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
            return $this->errorResponse(__('Vendor is not active.'), 404);
        }

        $userId = $request->user()?->id;
        $stories = $this->storyService->getVendorStories($vendor, $userId);

        if ($stories->isEmpty()) {
            return $this->errorResponse(__('No active stories found for this vendor.'), 404);
        }

        $nextVendor = $this->storyService->getNextVendor($vendor, $userId);

        return $this->successResponse([
            'vendor' => [
                'id' => $vendor->id,
                'name' => $vendor->name,
                'logo_url' => $vendor->avatar ? asset($vendor->avatar) : null,
                'country_code' => $vendor->businessProfile->country_code ?? null,
            ],
            'stories' => VendorStoryItemResource::collection($stories),
            'next_vendor_id' => $nextVendor?->id,
        ], __('Stories retrieved successfully.'));
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
            return $this->errorResponse(__('Story is not active.'), 404);
        }

        $this->storyService->markStoryAsViewed($story, $userId);
        
        $vendorHasUnread = $this->storyService->vendorHasUnread($story->vendor, $userId);

        return $this->successResponse([
            'vendor_has_unread' => $vendorHasUnread,
        ], __('Story marked as viewed.'));
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
            return $this->errorResponse(__('Vendor is not active.'), 404);
        }

        $count = $this->storyService->markVendorStoriesAsViewed($vendor, $userId);

        return $this->successResponse([
            'marked_count' => $count,
        ], __('All stories marked as viewed.'));
    }
}
