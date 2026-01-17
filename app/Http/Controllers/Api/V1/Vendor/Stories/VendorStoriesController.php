<?php

namespace App\Http\Controllers\Api\V1\Vendor\Stories;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Vendor\Stories\StoreStoryRequest;
use App\Http\Requests\Api\V1\Vendor\Stories\UpdateStoryRequest;
use App\Http\Resources\VendorStoryItemResource;
use App\Models\VendorStory;
use App\Services\VendorStoryService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class VendorStoriesController extends Controller
{
    use ApiResponseTrait;

    protected VendorStoryService $storyService;

    public function __construct(VendorStoryService $storyService)
    {
        $this->storyService = $storyService;
    }

    public function index(Request $request)
    {
        $vendor = $request->user();
        $perPage = (int) $request->input('per_page', 15);

        $query = VendorStory::where('vendor_id', $vendor->id)->ordered();

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $paginator = $query->paginate($perPage);

        $collection = VendorStoryItemResource::collection($paginator);
        return $this->paginated($collection);
    }

    public function store(StoreStoryRequest $request)
    {
        $vendor = $request->user();
        $data = $request->validated();

        $upload = $this->storyService->uploadMedia($request->file('media_file'), $vendor->id, $data['media_type']);

        $storyData = [
            'vendor_id' => $vendor->id,
            'title' => $data['title'] ?? null,
            'media_type' => $data['media_type'],
            'media_path' => $upload['media_path'],
            'duration_seconds' => $data['duration_seconds'] ?? ($data['media_type'] === 'image' ? 5 : null),
            'sort_order' => $data['sort_order'] ?? 0,
            'status' => $data['status'] ?? 'active',
            'start_at' => $data['start_at'] ?? null,
            'end_at' => $data['end_at'] ?? null,
        ];

        $story = VendorStory::create($storyData);

        return $this->success(new VendorStoryItemResource($story), 'follow.list_following');
    }

    public function update(UpdateStoryRequest $request, VendorStory $story)
    {
        $vendor = $request->user();
        if ($story->vendor_id !== $vendor->id) {
            return $this->error('forbidden', null, 403);
        }

        $data = $request->validated();

        if ($request->hasFile('media_file')) {
            // delete old
            $this->storyService->deleteMedia($story->media_path);
            $upload = $this->storyService->uploadMedia($request->file('media_file'), $vendor->id, $data['media_type'] ?? $story->media_type);
            $data['media_path'] = $upload['media_path'];
        }

        $data['duration_seconds'] = $data['duration_seconds'] ?? ($data['media_type'] ?? $story->media_type) === 'image' ? 5 : ($story->duration_seconds ?? null);

        $story->update($data);

        return $this->success(new VendorStoryItemResource($story), 'follow.list_following');
    }

    public function destroy(Request $request, VendorStory $story)
    {
        $vendor = $request->user();
        if ($story->vendor_id !== $vendor->id) {
            return $this->error('forbidden', null, 403);
        }

        $this->storyService->deleteMedia($story->media_path);
        $story->delete();

        return $this->success(null, 'deleted');
    }

    public function toggle(Request $request, VendorStory $story)
    {
        $vendor = $request->user();
        if ($story->vendor_id !== $vendor->id) {
            return $this->error('forbidden', null, 403);
        }

        $story->update(['status' => $story->status === 'active' ? 'inactive' : 'active']);

        return $this->success(null, 'updated');
    }

    public function analytics(Request $request, VendorStory $story)
    {
        $vendor = $request->user();
        if ($story->vendor_id !== $vendor->id) {
            return $this->error('forbidden', null, 403);
        }

        $data = $this->storyService->getStoryAnalytics($story);
        return $this->success($data, 'follow.list_followers');
    }
}
