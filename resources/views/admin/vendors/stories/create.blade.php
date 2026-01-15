@extends('admin.layouts.app')

@section('content')
<div class="container">
  <div class="card">
    <div class="toolbar">
      <div class="title-wrap">
        <h1 class="h1">{{ __('admin.create_story') ?? 'Create New Story' }}</h1>
        <div class="small p">{{ __('admin.create_story_subtitle') ?? 'Upload media and configure story settings' }}</div>
      </div>
      <div class="actions">
        <a href="{{ isset($vendor) ? route('admin.vendors.stories.index', $vendor) : route('admin.stories.index') }}" class="btn btn-ghost">{{ __('admin.back') ?? 'Back' }}</a>
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

    <form method="POST" action="{{ isset($vendor) ? route('admin.vendors.stories.store', $vendor) : route('admin.stories.store') }}" enctype="multipart/form-data" class="mt-6">
      @csrf

      <div class="grid-form">
        {{-- Vendor Selection --}}
        @if(!isset($vendor))
          <div class="form-group">
            <label class="label" for="vendor_id">{{ __('admin.vendor') ?? 'Vendor' }} *</label>
            <select name="vendor_id" id="vendor_id" class="input" required>
              <option value="">{{ __('admin.select_vendor') ?? '-- Select Vendor --' }}</option>
              @foreach($vendors as $v)
                <option value="{{ $v->id }}" {{ old('vendor_id') == $v->id ? 'selected' : '' }}>{{ $v->name }}</option>
              @endforeach
            </select>
          </div>
        @else
          <input type="hidden" name="vendor_id" value="{{ $vendor->id }}">
        @endif

        {{-- Title --}}
        <div class="form-group">
          <label class="label" for="title">{{ __('admin.title') ?? 'Title' }} ({{ __('admin.optional') ?? 'optional' }})</label>
          <input type="text" name="title" id="title" class="input" value="{{ old('title') }}" placeholder="{{ __('admin.story_title_placeholder') ?? 'e.g., New Product Launch' }}">
        </div>

        {{-- Media Type --}}
        <div class="form-group">
          <label class="label" for="media_type">{{ __('admin.media_type') ?? 'Media Type' }} *</label>
          <select name="media_type" id="media_type" class="input" required>
            <option value="image" {{ old('media_type', 'image') === 'image' ? 'selected' : '' }}>{{ __('admin.image') ?? 'Image' }}</option>
            <option value="video" {{ old('media_type') === 'video' ? 'selected' : '' }}>{{ __('admin.video') ?? 'Video' }}</option>
          </select>
        </div>

        {{-- Duration --}}
        <div class="form-group">
          <label class="label" for="duration_seconds">{{ __('admin.duration') ?? 'Duration' }} ({{ __('admin.seconds') ?? 'seconds' }})</label>
          <input type="number" name="duration_seconds" id="duration_seconds" class="input" value="{{ old('duration_seconds', 5) }}" min="1" max="60" placeholder="5">
          <small class="small">{{ __('admin.duration_hint') ?? 'Default: 5s for images, video length for videos' }}</small>
        </div>

        {{-- Status --}}
        <div class="form-group">
          <label class="label" for="status">{{ __('admin.status') ?? 'Status' }} *</label>
          <select name="status" id="status" class="input" required>
            <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>{{ __('admin.active') ?? 'Active' }}</option>
            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>{{ __('admin.inactive') ?? 'Inactive' }}</option>
          </select>
        </div>

        {{-- Sort Order --}}
        <div class="form-group">
          <label class="label" for="sort_order">{{ __('admin.sort_order') ?? 'Sort Order' }}</label>
          <input type="number" name="sort_order" id="sort_order" class="input" value="{{ old('sort_order', 0) }}" min="0" placeholder="0">
          <small class="small">{{ __('admin.sort_order_hint') ?? 'Lower numbers appear first' }}</small>
        </div>

        {{-- Start At --}}
        <div class="form-group">
          <label class="label" for="start_at">{{ __('admin.start_at') ?? 'Start Date' }} ({{ __('admin.optional') ?? 'optional' }})</label>
          <input type="datetime-local" name="start_at" id="start_at" class="input" value="{{ old('start_at') }}">
          <small class="small">{{ __('admin.start_at_hint') ?? 'Story becomes active at this time' }}</small>
        </div>

        {{-- End At --}}
        <div class="form-group">
          <label class="label" for="end_at">{{ __('admin.end_at') ?? 'End Date' }} ({{ __('admin.optional') ?? 'optional' }})</label>
          <input type="datetime-local" name="end_at" id="end_at" class="input" value="{{ old('end_at') }}">
          <small class="small">{{ __('admin.end_at_hint') ?? 'Story expires at this time' }}</small>
        </div>
      </div>

      {{-- Media Upload --}}
      <div class="form-group mt-6">
        <label class="label" for="media">{{ __('admin.media_file') ?? 'Media File' }} *</label>
        <input type="file" name="media" id="media" class="file-input" accept="image/jpeg,image/jpg,image/png,image/webp,video/mp4,video/quicktime" required data-upload-preview data-preview-target="#media-preview" data-filename-target="#media-filename">
        <small class="small">{{ __('admin.media_hint') ?? 'Supported: JPG, PNG, WebP (images), MP4, MOV (videos). Max size: 50MB' }}</small>
        
        <div style="margin-top:1rem;display:flex;gap:1rem;align-items:center">
          <div id="media-preview" class="upload-preview upload-placeholder" style="width:120px;height:120px">
            <div class="placeholder-icon">📷</div>
            <div class="placeholder-text">{{ __('admin.choose_file') ?? 'Choose file' }}</div>
          </div>
          <div>
            <div id="media-filename" class="small"></div>
          </div>
        </div>
      </div>

      {{-- Submit --}}
      <div class="mt-6" style="display:flex;gap:.6rem">
        <button type="submit" class="btn btn-gold">{{ __('admin.create_story') ?? 'Create Story' }}</button>
        <a href="{{ isset($vendor) ? route('admin.vendors.stories.index', $vendor) : route('admin.stories.index') }}" class="btn btn-ghost">{{ __('admin.cancel') ?? 'Cancel' }}</a>
      </div>
    </form>
  </div>
</div>
@endsection
