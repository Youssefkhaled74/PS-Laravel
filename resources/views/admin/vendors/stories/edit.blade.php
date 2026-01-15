@extends('admin.layouts.app')

@section('content')
<div class="container">
  <div class="card">
    <div class="toolbar">
      <div class="title-wrap">
        <h1 class="h1">{{ __('admin.edit_story') ?? 'Edit Story' }}</h1>
        <div class="small p">{{ __('admin.edit_story_subtitle') ?? 'Update story settings and media' }}</div>
      </div>
      <div class="actions">
        <a href="{{ route('admin.vendors.stories.index', $story->vendor) }}" class="btn btn-ghost">{{ __('admin.back') ?? 'Back' }}</a>
      </div>
    </div>

    @if($errors->any())
      <div class="alert alert-danger mt-6">
        <span>⚠</span>
        <div>
          <strong>{{ __('admin.validation_errors') ?? 'Please fix the following errors:' }}</strong>
          <ul style="margin:.5rem 0 0;padding-left:1.25rem">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      </div>
    @endif

    <form method="POST" action="{{ route('admin.stories.update', $story) }}" enctype="multipart/form-data" class="mt-6">
      @csrf
      @method('PUT')

      <div class="grid-form">
        {{-- Vendor Selection --}}
        <div class="form-group">
          <label class="label" for="vendor_id">{{ __('admin.vendor') ?? 'Vendor' }} *</label>
          <select name="vendor_id" id="vendor_id" class="input" required>
            @foreach($vendors as $v)
              <option value="{{ $v->id }}" {{ old('vendor_id', $story->vendor_id) == $v->id ? 'selected' : '' }}>{{ $v->name }}</option>
            @endforeach
          </select>
        </div>

        {{-- Title --}}
        <div class="form-group">
          <label class="label" for="title">{{ __('admin.title') ?? 'Title' }} ({{ __('admin.optional') ?? 'optional' }})</label>
          <input type="text" name="title" id="title" class="input" value="{{ old('title', $story->title) }}" placeholder="{{ __('admin.story_title_placeholder') ?? 'e.g., New Product Launch' }}">
        </div>

        {{-- Media Type --}}
        <div class="form-group">
          <label class="label" for="media_type">{{ __('admin.media_type') ?? 'Media Type' }} *</label>
          <select name="media_type" id="media_type" class="input" required>
            <option value="image" {{ old('media_type', $story->media_type) === 'image' ? 'selected' : '' }}>{{ __('admin.image') ?? 'Image' }}</option>
            <option value="video" {{ old('media_type', $story->media_type) === 'video' ? 'selected' : '' }}>{{ __('admin.video') ?? 'Video' }}</option>
          </select>
        </div>

        {{-- Duration --}}
        <div class="form-group">
          <label class="label" for="duration_seconds">{{ __('admin.duration') ?? 'Duration' }} ({{ __('admin.seconds') ?? 'seconds' }})</label>
          <input type="number" name="duration_seconds" id="duration_seconds" class="input" value="{{ old('duration_seconds', $story->duration_seconds) }}" min="1" max="60" placeholder="5">
          <small class="small">{{ __('admin.duration_hint') ?? 'Default: 5s for images, video length for videos' }}</small>
        </div>

        {{-- Status --}}
        <div class="form-group">
          <label class="label" for="status">{{ __('admin.status') ?? 'Status' }} *</label>
          <select name="status" id="status" class="input" required>
            <option value="active" {{ old('status', $story->status) === 'active' ? 'selected' : '' }}>{{ __('admin.active') ?? 'Active' }}</option>
            <option value="inactive" {{ old('status', $story->status) === 'inactive' ? 'selected' : '' }}>{{ __('admin.inactive') ?? 'Inactive' }}</option>
          </select>
        </div>

        {{-- Sort Order --}}
        <div class="form-group">
          <label class="label" for="sort_order">{{ __('admin.sort_order') ?? 'Sort Order' }}</label>
          <input type="number" name="sort_order" id="sort_order" class="input" value="{{ old('sort_order', $story->sort_order) }}" min="0" placeholder="0">
          <small class="small">{{ __('admin.sort_order_hint') ?? 'Lower numbers appear first' }}</small>
        </div>

        {{-- Start At --}}
        <div class="form-group">
          <label class="label" for="start_at">{{ __('admin.start_at') ?? 'Start Date' }} ({{ __('admin.optional') ?? 'optional' }})</label>
          <input type="datetime-local" name="start_at" id="start_at" class="input" value="{{ old('start_at', $story->start_at?->format('Y-m-d\TH:i')) }}">
          <small class="small">{{ __('admin.start_at_hint') ?? 'Story becomes active at this time' }}</small>
        </div>

        {{-- End At --}}
        <div class="form-group">
          <label class="label" for="end_at">{{ __('admin.end_at') ?? 'End Date' }} ({{ __('admin.optional') ?? 'optional' }})</label>
          <input type="datetime-local" name="end_at" id="end_at" class="input" value="{{ old('end_at', $story->end_at?->format('Y-m-d\TH:i')) }}">
          <small class="small">{{ __('admin.end_at_hint') ?? 'Story expires at this time' }}</small>
        </div>
      </div>

      {{-- Current Media --}}
      <div class="form-group mt-6">
        <label class="label">{{ __('admin.current_media') ?? 'Current Media' }}</label>
        <div style="margin-top:.5rem">
          @if($story->media_type === 'image')
            <img src="{{ $story->media_url }}" alt="Story media" style="max-width:300px;border-radius:12px;border:1px solid var(--card-soft-border)">
          @else
            <video src="{{ $story->media_url }}" controls style="max-width:300px;border-radius:12px;border:1px solid var(--card-soft-border)"></video>
          @endif
        </div>
      </div>

      {{-- Media Upload (Optional) --}}
      <div class="form-group mt-6">
        <label class="label" for="media">{{ __('admin.replace_media') ?? 'Replace Media' }} ({{ __('admin.optional') ?? 'optional' }})</label>
        <input type="file" name="media" id="media" class="file-input" accept="image/jpeg,image/jpg,image/png,image/webp,video/mp4,video/quicktime" data-upload-preview data-preview-target="#media-preview" data-filename-target="#media-filename" data-initial-url="{{ $story->media_url }}">
        <small class="small">{{ __('admin.media_hint') ?? 'Supported: JPG, PNG, WebP (images), MP4, MOV (videos). Max size: 50MB' }}</small>
        
        <div style="margin-top:1rem;display:flex;gap:1rem;align-items:center">
          <img id="media-preview" src="{{ $story->media_url }}" class="upload-preview" style="width:120px;height:120px;object-fit:cover" alt="Preview">
          <div>
            <div id="media-filename" class="small"></div>
          </div>
        </div>
      </div>

      {{-- Submit --}}
      <div class="mt-6" style="display:flex;gap:.6rem">
        <button type="submit" class="btn btn-gold">{{ __('admin.update_story') ?? 'Update Story' }}</button>
        <a href="{{ route('admin.vendors.stories.index', $story->vendor) }}" class="btn btn-ghost">{{ __('admin.cancel') ?? 'Cancel' }}</a>
      </div>
    </form>
  </div>
</div>
@endsection
