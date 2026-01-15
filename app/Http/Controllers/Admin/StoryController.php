<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\VendorStory;
use App\Services\VendorStoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StoryController extends Controller
{
    protected VendorStoryService $storyService;

    public function __construct(VendorStoryService $storyService)
    {
        $this->storyService = $storyService;
    }

    /**
     * Display a listing of all stories across vendors
     */
    public function index(Request $request)
    {
        $query = VendorStory::with('vendor')->ordered();

        // Search by vendor name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('vendor', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filter by media type
        if ($request->filled('media_type') && $request->media_type !== '') {
            $query->where('media_type', $request->media_type);
        }

        // Filter by schedule
        if ($request->filled('schedule') && $request->schedule !== '') {
            $now = now();
            switch ($request->schedule) {
                case 'active':
                    $query->active();
                    break;
                case 'upcoming':
                    $query->where('status', 'active')
                        ->where('start_at', '>', $now);
                    break;
                case 'expired':
                    $query->where('end_at', '<', $now);
                    break;
            }
        }

        $perPage = $request->input('per_page', 15);
        $stories = $query->paginate($perPage)->withQueryString();
        
        $totalCount = VendorStory::count();
        $vendors = Vendor::where('status', 'active')->orderBy('name')->get();

        return view('admin.stories.index', compact('stories', 'totalCount', 'vendors'));
    }

    /**
     * Show the form for creating a new story
     */
    public function create()
    {
        $vendors = Vendor::where('status', 'active')->orderBy('name')->get();
        return view('admin.stories.create', compact('vendors'));
    }

    /**
     * Store a newly created story
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vendor_id' => 'required|exists:vendors,id',
            'title' => 'nullable|string|max:255',
            'media_type' => 'required|in:image,video',
            'media' => 'required|file|mimes:jpg,jpeg,png,webp,mp4,mov|max:51200', // 50MB max
            'duration_seconds' => 'nullable|integer|min:1|max:60',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive',
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date|after:start_at',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $validator->validated();

        // Upload media
        $upload = $this->storyService->uploadMedia(
            $request->file('media'),
            $data['vendor_id'],
            $data['media_type']
        );

        $data['media_path'] = $upload['media_path'];
        $data['duration_seconds'] = $data['duration_seconds'] ?? ($data['media_type'] === 'image' ? 5 : null);

        VendorStory::create($data);

        return redirect()
            ->route('admin.stories.index')
            ->with('success', __('admin.stories.created_successfully'));
    }

    /**
     * Show the form for editing a story
     */
    public function edit(VendorStory $story)
    {
        $vendors = Vendor::where('status', 'active')->orderBy('name')->get();
        return view('admin.stories.edit', compact('story', 'vendors'));
    }

    /**
     * Update the specified story
     */
    public function update(Request $request, VendorStory $story)
    {
        $validator = Validator::make($request->all(), [
            'vendor_id' => 'required|exists:vendors,id',
            'title' => 'nullable|string|max:255',
            'media_type' => 'required|in:image,video',
            'media' => 'nullable|file|mimes:jpg,jpeg,png,webp,mp4,mov|max:51200',
            'duration_seconds' => 'nullable|integer|min:1|max:60',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive',
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date|after:start_at',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $validator->validated();

        // Upload new media if provided
        if ($request->hasFile('media')) {
            // Delete old media
            $this->storyService->deleteMedia($story->media_path);

            $upload = $this->storyService->uploadMedia(
                $request->file('media'),
                $data['vendor_id'],
                $data['media_type']
            );

            $data['media_path'] = $upload['media_path'];
        }

        $data['duration_seconds'] = $data['duration_seconds'] ?? ($data['media_type'] === 'image' ? 5 : null);

        $story->update($data);

        return redirect()
            ->route('admin.stories.index')
            ->with('success', __('admin.stories.updated_successfully'));
    }

    /**
     * Remove the specified story
     */
    public function destroy(VendorStory $story)
    {
        // Delete media file
        $this->storyService->deleteMedia($story->media_path);
        
        $story->delete();

        return redirect()
            ->route('admin.stories.index')
            ->with('success', __('admin.stories.deleted_successfully'));
    }

    /**
     * Toggle story status
     */
    public function toggleStatus(VendorStory $story)
    {
        $story->update([
            'status' => $story->status === 'active' ? 'inactive' : 'active',
        ]);

        return redirect()->back()
            ->with('success', __('admin.stories.status_updated'));
    }
}
