<?php

namespace App\Services;

use App\Models\Vendor;
use App\Models\VendorStory;
use App\Models\VendorStoryView;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class VendorStoryService
{
    /**
     * Get vendors that have active stories with unread status for user
     */
    public function getVendorsWithStories(?int $userId = null)
    {
        return Vendor::where('status', 'active')
            ->whereHas('activeStories')
            ->withCount('activeStories')
            ->with(['activeStories' => function ($query) {
                $query->latest('created_at')->limit(1);
            }])
            ->get()
            ->map(function ($vendor) use ($userId) {
                $hasUnread = false;
                
                if ($userId) {
                    // Check if user has any unread stories for this vendor
                    $activeStoryIds = $vendor->activeStories->pluck('id');
                    $viewedStoryIds = VendorStoryView::where('user_id', $userId)
                        ->whereIn('vendor_story_id', $activeStoryIds)
                        ->pluck('vendor_story_id');
                    
                    $hasUnread = $activeStoryIds->count() > $viewedStoryIds->count();
                }

                return [
                    'vendor' => $vendor,
                    'has_unread' => $hasUnread,
                    'stories_count' => $vendor->active_stories_count,
                    'latest_story_created_at' => $vendor->activeStories->first()?->created_at,
                ];
            })
            ->sortByDesc('has_unread')
            ->values();
    }

    /**
     * Get active stories for a vendor
     */
    public function getVendorStories(Vendor $vendor, ?int $userId = null)
    {
        $stories = $vendor->activeStories()->get();

        if ($userId) {
            $viewedIds = VendorStoryView::where('user_id', $userId)
                ->whereIn('vendor_story_id', $stories->pluck('id'))
                ->pluck('vendor_story_id')
                ->toArray();

            $stories = $stories->map(function ($story) use ($viewedIds) {
                $story->is_viewed = in_array($story->id, $viewedIds);
                return $story;
            });
        }

        return $stories;
    }

    /**
     * Mark story as viewed by user
     */
    public function markStoryAsViewed(VendorStory $story, int $userId): bool
    {
        return VendorStoryView::updateOrCreate(
            [
                'vendor_story_id' => $story->id,
                'user_id' => $userId,
            ],
            [
                'viewed_at' => now(),
            ]
        ) !== null;
    }

    /**
     * Mark all vendor stories as viewed by user
     */
    public function markVendorStoriesAsViewed(Vendor $vendor, int $userId): int
    {
        $storyIds = $vendor->activeStories()->pluck('id');
        $count = 0;

        foreach ($storyIds as $storyId) {
            VendorStoryView::updateOrCreate(
                [
                    'vendor_story_id' => $storyId,
                    'user_id' => $userId,
                ],
                [
                    'viewed_at' => now(),
                ]
            );
            $count++;
        }

        return $count;
    }

    /**
     * Check if vendor has unread stories for user
     */
    public function vendorHasUnread(Vendor $vendor, int $userId): bool
    {
        $activeStoryIds = $vendor->activeStories()->pluck('id');
        $viewedStoryIds = VendorStoryView::where('user_id', $userId)
            ->whereIn('vendor_story_id', $activeStoryIds)
            ->pluck('vendor_story_id');

        return $activeStoryIds->count() > $viewedStoryIds->count();
    }

    /**
     * Upload story media
     */
    public function uploadMedia($file, int $vendorId, string $type = 'image'): array
    {
        $directory = "uploads/stories/{$vendorId}";
        
        if (!file_exists(public_path($directory))) {
            mkdir(public_path($directory), 0755, true);
        }

        $extension = $file->getClientOriginalExtension();
        $filename = uniqid() . '_' . time() . '.' . $extension;
        $path = $directory . '/' . $filename;

        $file->move(public_path($directory), $filename);

        return [
            'media_path' => $path,
            'media_url' => asset($path),
        ];
    }

    /**
     * Delete story media
     */
    public function deleteMedia(string $mediaPath): bool
    {
        $fullPath = public_path($mediaPath);
        
        if (file_exists($fullPath)) {
            return unlink($fullPath);
        }

        return false;
    }

    /**
     * Get next vendor with stories
     */
    public function getNextVendor(Vendor $currentVendor, ?int $userId = null): ?Vendor
    {
        $vendors = $this->getVendorsWithStories($userId);
        $currentIndex = $vendors->search(function ($item) use ($currentVendor) {
            return $item['vendor']->id === $currentVendor->id;
        });

        if ($currentIndex !== false && isset($vendors[$currentIndex + 1])) {
            return $vendors[$currentIndex + 1]['vendor'];
        }

        return null;
    }

    /**
     * Get story analytics (views count)
     */
    public function getStoryAnalytics(VendorStory $story): array
    {
        return [
            'views_count' => $story->views()->count(),
            'unique_viewers' => $story->views()->distinct('user_id')->count('user_id'),
        ];
    }

    /**
     * Get vendor stories analytics
     */
    public function getVendorAnalytics(Vendor $vendor): array
    {
        $stories = $vendor->stories;
        $totalViews = VendorStoryView::whereIn('vendor_story_id', $stories->pluck('id'))->count();
        $uniqueViewers = VendorStoryView::whereIn('vendor_story_id', $stories->pluck('id'))
            ->distinct('user_id')
            ->count('user_id');

        return [
            'total_stories' => $stories->count(),
            'active_stories' => $vendor->activeStories()->count(),
            'total_views' => $totalViews,
            'unique_viewers' => $uniqueViewers,
        ];
    }
}
