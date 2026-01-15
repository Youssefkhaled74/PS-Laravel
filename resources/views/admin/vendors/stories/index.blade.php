@extends('admin.layouts.app')

@section('content')
<div class="container">
  <div class="card">
    <div class="toolbar">
      <div class="title-wrap">
        <h1 class="h1">{{ __('admin.stories.title') ?? 'Vendor Stories' }}</h1>
        <div class="small p">{{ __('admin.stories.subtitle') ?? 'Manage vendor stories (Instagram-like)' }}</div>
      </div>
      <div class="actions">
        @if($vendor ?? false)
          <a href="{{ route('admin.vendors.show', $vendor) }}" class="btn btn-ghost">{{ __('admin.back_to_vendor') ?? 'Back to Vendor' }}</a>
        @endif
        <a href="{{ isset($vendor) ? route('admin.vendors.stories.create', $vendor) : route('admin.stories.create') }}" class="btn btn-gold">{{ __('admin.create_story') ?? '+ New Story' }}</a>
      </div>
    </div>

    {{-- Filters --}}
    <div class="card-soft mt-6">
      <form method="GET" class="filters">
        @if(!isset($vendor))
          <select name="vendor_id" class="input" style="max-width:220px" onchange="this.form.submit()">
            <option value="">{{ __('admin.all_vendors') ?? 'All Vendors' }}</option>
            @foreach($vendors as $v)
              <option value="{{ $v->id }}" {{ request('vendor_id') == $v->id ? 'selected' : '' }}>
                {{ $v->name }}
              </option>
            @endforeach
          </select>
        @endif

        <select name="status" class="input" style="max-width:160px" onchange="this.form.submit()">
          <option value="">{{ __('admin.all_statuses') ?? 'All Statuses' }}</option>
          <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>{{ __('admin.active') ?? 'Active' }}</option>
          <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>{{ __('admin.inactive') ?? 'Inactive' }}</option>
        </select>

        <select name="schedule" class="input" style="max-width:180px" onchange="this.form.submit()">
          <option value="">{{ __('admin.all_schedules') ?? 'All Schedules' }}</option>
          <option value="active" {{ request('schedule') === 'active' ? 'selected' : '' }}>{{ __('admin.active_now') ?? 'Active Now' }}</option>
          <option value="upcoming" {{ request('schedule') === 'upcoming' ? 'selected' : '' }}>{{ __('admin.upcoming') ?? 'Upcoming' }}</option>
          <option value="expired" {{ request('schedule') === 'expired' ? 'selected' : '' }}>{{ __('admin.expired') ?? 'Expired' }}</option>
        </select>

        @if(request()->hasAny(['vendor_id', 'status', 'schedule']))
          <a href="{{ isset($vendor) ? route('admin.vendors.stories.index', $vendor) : route('admin.stories.index') }}" class="btn btn-ghost btn-sm">{{ __('admin.clear_filters') ?? 'Clear' }}</a>
        @endif
      </form>
    </div>

    @if(session('success'))
      <div class="alert alert-success mt-6">
        <span>✓</span>
        <div>{{ session('success') }}</div>
      </div>
    @endif

    {{-- Stories Table --}}
    <div class="table-wrap mt-6">
      <table class="table">
        <thead>
          <tr>
            <th style="width:80px">{{ __('admin.preview') ?? 'Preview' }}</th>
            <th>{{ __('admin.vendor') ?? 'Vendor' }}</th>
            <th>{{ __('admin.title') ?? 'Title' }}</th>
            <th style="width:100px">{{ __('admin.type') ?? 'Type' }}</th>
            <th style="width:100px">{{ __('admin.duration') ?? 'Duration' }}</th>
            <th style="width:120px">{{ __('admin.status') ?? 'Status' }}</th>
            <th style="width:120px">{{ __('admin.schedule') ?? 'Schedule' }}</th>
            <th style="width:80px">{{ __('admin.sort') ?? 'Sort' }}</th>
            <th style="width:180px">{{ __('admin.actions') ?? 'Actions' }}</th>
          </tr>
        </thead>
        <tbody>
          @forelse($stories as $story)
            <tr>
              <td>
                @if($story->media_type === 'image')
                  <img src="{{ $story->media_url }}" alt="Story" class="logo-thumb" style="width:60px;height:60px;object-fit:cover">
                @else
                  <div class="logo-thumb" style="width:60px;height:60px;background:var(--overlay-2);display:flex;align-items:center;justify-content:center">
                    🎥
                  </div>
                @endif
              </td>
              <td>
                <a href="{{ route('admin.vendors.show', $story->vendor) }}" class="ps-link">{{ $story->vendor->name }}</a>
              </td>
              <td>{{ $story->title ?: '-' }}</td>
              <td>
                <span class="badge-neutral">{{ ucfirst($story->media_type) }}</span>
              </td>
              <td>{{ $story->duration_seconds }}s</td>
              <td>
                <form method="POST" action="{{ isset($vendor) ? route('admin.vendors.stories.toggle', [$vendor, $story]) : route('admin.stories.toggle', $story) }}" style="display:inline">
                  @csrf
                  @method('PATCH')
                  <button type="submit" class="btn-sm {{ $story->status === 'active' ? 'btn-success' : 'btn-ghost' }}">
                    {{ ucfirst($story->status) }}
                  </button>
                </form>
              </td>
              <td>
                @php
                  $scheduleStatus = $story->getScheduleStatus();
                  $badgeClass = match($scheduleStatus) {
                    'active' => 'badge-neutral',
                    'upcoming' => 'badge-warning',
                    'expired' => 'badge-muted',
                    'inactive' => 'badge-muted',
                    default => 'badge-neutral'
                  };
                @endphp
                <span class="{{ $badgeClass }}">{{ ucfirst($scheduleStatus) }}</span>
              </td>
              <td>{{ $story->sort_order }}</td>
              <td>
                <div class="table-actions">
                  <a href="{{ isset($vendor) ? route('admin.vendors.stories.edit', [$vendor, $story]) : route('admin.stories.edit', $story) }}" class="btn-sm btn-ghost">{{ __('admin.edit') ?? 'Edit' }}</a>
                  <form method="POST" action="{{ isset($vendor) ? route('admin.vendors.stories.destroy', [$vendor, $story]) : route('admin.stories.destroy', $story) }}" style="display:inline" class="js-confirm" data-confirm="{{ __('admin.confirm_delete_story') ?? 'Delete this story?' }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-sm btn-danger">{{ __('admin.delete') ?? 'Delete' }}</button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="9" style="text-align:center;padding:2rem;color:var(--muted)">
                {{ __('admin.no_stories_found') ?? 'No stories found.' }}
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($stories->hasPages())
      <div class="mt-6">
        {{ $stories->links() }}
      </div>
    @endif
  </div>
</div>
@endsection
