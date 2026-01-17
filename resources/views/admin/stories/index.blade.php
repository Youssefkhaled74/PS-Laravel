@extends('admin.layouts.app')

@section('content')
<div class="container">
  <div class="card">
    <div class="toolbar">
      <div class="title-wrap">
        <div style="display:flex;align-items:center;gap:.6rem">
          <h1 class="h1">{{ __('admin.stories.title') ?? 'Stories' }}</h1>
          <span class="badge-neutral">{{ $totalCount }}</span>
        </div>
        <div class="small p">{{ __('admin.stories.subtitle') ?? 'Manage all vendor stories across the platform' }}</div>
      </div>
    </div>

    @if(session('success'))
      <div class="alert alert-success mt-6">
        <span>✓</span>
        <div>{{ session('success') }}</div>
      </div>
    @endif

    {{-- Filters --}}
    <div class="card-soft mt-6">
      <form method="GET" action="{{ route('admin.stories.index') }}" class="filters">
        <div class="form-group" style="flex:2;min-width:200px">
          <input 
            type="text" 
            name="search" 
            class="input" 
            placeholder="{{ __('admin.stories.search_vendor') ?? 'Search by vendor name...' }}" 
            value="{{ request('search') }}"
          >
        </div>

        <div class="form-group" style="flex:1;min-width:140px">
          <select name="status" class="input">
            <option value="">{{ __('admin.stories.all_statuses') ?? 'All Statuses' }}</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>{{ __('admin.active') ?? 'Active' }}</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>{{ __('admin.inactive') ?? 'Inactive' }}</option>
          </select>
        </div>

        <div class="form-group" style="flex:1;min-width:140px">
          <select name="media_type" class="input">
            <option value="">{{ __('admin.stories.all_types') ?? 'All Types' }}</option>
            <option value="image" {{ request('media_type') === 'image' ? 'selected' : '' }}>{{ __('admin.stories.image') ?? 'Image' }}</option>
            <option value="video" {{ request('media_type') === 'video' ? 'selected' : '' }}>{{ __('admin.stories.video') ?? 'Video' }}</option>
          </select>
        </div>

        <div class="form-group" style="flex:1;min-width:140px">
          <select name="schedule" class="input">
            <option value="">{{ __('admin.stories.all_schedules') ?? 'All Schedules' }}</option>
            <option value="active" {{ request('schedule') === 'active' ? 'selected' : '' }}>{{ __('admin.stories.active_now') ?? 'Active Now' }}</option>
            <option value="upcoming" {{ request('schedule') === 'upcoming' ? 'selected' : '' }}>{{ __('admin.stories.upcoming') ?? 'Upcoming' }}</option>
            <option value="expired" {{ request('schedule') === 'expired' ? 'selected' : '' }}>{{ __('admin.stories.expired') ?? 'Expired' }}</option>
          </select>
        </div>

        <div class="form-group" style="flex:0;min-width:100px">
          <select name="per_page" class="input">
            <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15</option>
            <option value="30" {{ request('per_page') == 30 ? 'selected' : '' }}>30</option>
            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
          </select>
        </div>

        <button type="submit" class="btn btn-ghost">{{ __('admin.filter') ?? 'Filter' }}</button>
        
        @if(request()->hasAny(['search', 'status', 'media_type', 'schedule', 'per_page']))
          <a href="{{ route('admin.stories.index') }}" class="btn btn-ghost">{{ __('admin.clear') ?? 'Clear' }}</a>
        @endif
      </form>
    </div>

    {{-- Table --}}
    <div class="table-wrap mt-6">
      @if($stories->isEmpty())
        <div class="p" style="padding:2rem;text-align:center;color:var(--muted)">
          {{ __('admin.stories.no_stories') ?? 'No stories found.' }}
        </div>
      @else
        <table class="table">
          <thead>
            <tr>
              <th>{{ __('admin.stories.vendor') ?? 'Vendor' }}</th>
              <th>{{ __('admin.stories.media') ?? 'Media' }}</th>
              <th>{{ __('admin.stories.type') ?? 'Type' }}</th>
              <th>{{ __('admin.stories.status') ?? 'Status' }}</th>
              <th>{{ __('admin.stories.schedule') ?? 'Schedule' }}</th>
              <th>{{ __('admin.stories.views') ?? 'Views' }}</th>
              <th>{{ __('admin.created_at') ?? 'Created' }}</th>
              <th>{{ __('admin.actions') ?? 'Actions' }}</th>
            </tr>
          </thead>
          <tbody>
            @foreach($stories as $story)
              <tr>
                {{-- Vendor --}}
                <td>
                  <div style="display:flex;align-items:center;gap:.5rem">
                    @if($story->vendor->avatar)
                      <img src="{{ asset($story->vendor->avatar) }}" alt="{{ $story->vendor->name }}" class="logo-thumb" style="width:32px;height:32px;border-radius:50%">
                    @else
                      <div style="width:32px;height:32px;border-radius:50%;background:var(--gold);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.8rem">
                        {{ mb_substr($story->vendor->name, 0, 1) }}
                      </div>
                    @endif
                    <div>
                      <div style="font-weight:600">{{ $story->vendor->name }}</div>
                      @if($story->title)
                        <div class="small" style="color:var(--muted)">{{ $story->title }}</div>
                      @endif
                    </div>
                  </div>
                </td>

                {{-- Media Preview --}}
                <td>
                  @if($story->media_type === 'image')
                    <img src="{{ $story->media_url }}" alt="Story" style="width:60px;height:60px;object-fit:cover;border-radius:8px;border:1px solid var(--card-soft-border)">
                  @else
                    <div style="width:60px;height:60px;border-radius:8px;border:1px solid var(--card-soft-border);background:var(--overlay-2);display:flex;align-items:center;justify-content:center">
                      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polygon points="5 3 19 12 5 21 5 3"></polygon>
                      </svg>
                    </div>
                  @endif
                </td>

                {{-- Type --}}
                <td>
                  @if($story->media_type === 'image')
                    <span class="badge-neutral">{{ __('admin.stories.image') ?? 'Image' }}</span>
                  @else
                    <span class="badge-warning">{{ __('admin.stories.video') ?? 'Video' }}</span>
                  @endif
                  <div class="small" style="color:var(--muted);margin-top:.25rem">{{ $story->duration_seconds }}s</div>
                </td>

                {{-- Status with Toggle --}}
                <td>
                  <x-admin.status-toggle 
                    :action="route('admin.stories.toggle', $story)" 
                    :status="$story->status"
                    :activeLabel="__('admin.active')"
                    :inactiveLabel="__('admin.inactive')"
                    :confirmTitle="__('admin.stories.confirm_status_change')"
                    :confirmText="__('admin.stories.confirm_status_change_text')"
                    size="sm"
                  />
                </td>

                {{-- Schedule Status --}}
                <td>
                  @php
                    $scheduleStatus = $story->getScheduleStatus();
                  @endphp
                  @if($scheduleStatus === 'active')
                    <span class="badge-neutral" style="background:rgba(34,197,94,.08);color:var(--success)">{{ __('admin.stories.active_now') ?? 'Active' }}</span>
                  @elseif($scheduleStatus === 'upcoming')
                    <span class="badge-warning">{{ __('admin.stories.upcoming') ?? 'Upcoming' }}</span>
                  @elseif($scheduleStatus === 'expired')
                    <span class="badge-neutral" style="background:rgba(239,68,68,.08);color:var(--danger)">{{ __('admin.stories.expired') ?? 'Expired' }}</span>
                  @else
                    <span class="badge-neutral">{{ __('admin.inactive') ?? 'Inactive' }}</span>
                  @endif
                  
                  @if($story->start_at || $story->end_at)
                    <div class="small" style="color:var(--muted);margin-top:.25rem">
                      @if($story->start_at)
                        {{ $story->start_at->format('M d') }}
                      @endif
                      @if($story->start_at && $story->end_at)
                        →
                      @endif
                      @if($story->end_at)
                        {{ $story->end_at->format('M d') }}
                      @endif
                    </div>
                  @endif
                </td>

                {{-- Views --}}
                <td>
                  <div style="font-weight:600">{{ $story->views()->count() }}</div>
                  <div class="small" style="color:var(--muted)">{{ __('admin.stories.views') ?? 'views' }}</div>
                </td>

                {{-- Created At --}}
                <td>
                  <div>{{ $story->created_at->format('M d, Y') }}</div>
                  <div class="small" style="color:var(--muted)">{{ $story->created_at->format('h:i A') }}</div>
                </td>

                {{-- Actions --}}
                <td>
                  <div class="table-actions">
                    <a href="{{ route('admin.stories.edit', $story) }}" class="btn btn-sm btn-ghost" title="{{ __('admin.edit') ?? 'Edit' }}">
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                      </svg>
                    </a>

                    <form method="POST" action="{{ route('admin.stories.destroy', $story) }}" style="display:inline">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-danger js-confirm" data-confirm="{{ __('admin.stories.confirm_delete') ?? 'Are you sure you want to delete this story?' }}" data-confirm-title="{{ __('admin.confirm') ?? 'Confirm' }}" title="{{ __('admin.delete') ?? 'Delete' }}">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                          <polyline points="3 6 5 6 21 6"></polyline>
                          <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        </svg>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      @endif
    </div>

    {{-- Pagination --}}
    @if($stories->hasPages())
      <div class="mt-6" style="display:flex;justify-content:center">
        <style>
          /* Pagination overrides for admin theme to prevent oversized icons */
          nav[role="navigation"] { display:inline-block; }
          nav[role="navigation"] ul { display:flex; gap:8px; list-style:none; padding:0; margin:0; align-items:center; }
          nav[role="navigation"] li { display:inline-block; }
          nav[role="navigation"] a, nav[role="navigation"] span, .page-link { display:inline-flex !important; align-items:center; justify-content:center; padding:6px 10px !important; font-size:14px !important; height:auto !important; width:auto !important; border-radius:8px !important; }
          nav[role="navigation"] svg { width:18px !important; height:18px !important; }
        </style>
        {{ $stories->links() }}
      </div>
    @endif
  </div>
</div>
@endsection
