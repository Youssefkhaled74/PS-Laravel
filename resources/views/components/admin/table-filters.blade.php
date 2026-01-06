@props([
  'action' => url()->current(),
  'resetUrl' => null,
  'showStatus' => false,
  'showPerPage' => false,
  'searchPlaceholder' => null,
])

<div class="filters-card">
  <form method="GET" action="{{ $action }}" aria-label="Table filters">
    <div class="filters-grid">
      <div class="field">
        <label class="field-label sr-only">{{ __('admin.search') }}</label>
        <input type="search" name="search" class="input control" placeholder="{{ $searchPlaceholder ?? __('admin.search') }}" value="{{ request('search') }}" />
      </div>

      @if($showStatus)
        <div class="field">
          <label class="field-label sr-only">{{ __('admin.status') }}</label>
          <select name="status" class="input select control">
            <option value="">{{ __('admin.all') }}</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>{{ __('admin.active') }}</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>{{ __('admin.inactive') }}</option>
          </select>
        </div>
      @endif

      @if($showPerPage)
        <div class="field">
          <label class="field-label sr-only">{{ __('admin.per_page') }}</label>
          <select name="per_page" class="input select control">
            @foreach([10,15,25,50] as $pp)
              <option value="{{ $pp }}" {{ (int) request('per_page', 10) === $pp ? 'selected' : '' }}>{{ $pp }}</option>
            @endforeach
          </select>
        </div>
      @endif

      {{-- extra slot for custom filters --}}
      @if(isset($extra))
        <div class="field">
          {{ $extra }}
        </div>
      @endif

      <div class="field btn-group">
        <button type="submit" class="btn btn-ghost btn-sm">{{ __('admin.filter') }}</button>

        @php
          $hasFilters = request()->has('search') || request()->has('status') || (request()->filled('per_page') && request('per_page') != 10);
          $reset = $resetUrl ?? $action;
        @endphp

        @if($hasFilters)
          <a href="{{ $reset }}" class="btn btn-ghost btn-sm">{{ __('admin.reset') }}</a>
        @endif

        {{-- right-side actions slot --}}
        @if(isset($actions))
          <div style="display:inline-flex;margin-left:.5rem">{{ $actions }}</div>
        @endif
      </div>
    </div>
  </form>
</div>
