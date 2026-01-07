@extends('admin.layouts.app')

@section('content')
  <div class="card">
    <div class="toolbar">
      <div class="title-wrap">
        <h1 class="h1">{{ __('admin.brands.title') }}</h1>
        <div class="small p">{{ __('admin.brands.subtitle') }}</div>
      </div>
      <div class="actions">
        <a href="{{ route('admin.brands.create') }}" class="btn btn-gold">{{ __('admin.brands.add') }}</a>
      </div>
    </div>

    <x-admin.table-filters
      :action="route('admin.brands.index')"
      :resetUrl="route('admin.brands.index')"
      :showStatus="true"
      :showPerPage="true"
      searchPlaceholder="{{ __('admin.brands.search_placeholder') }}"
    />

    <div class="mt-6 card table-wrap">
      @if($brands->count())
        <table class="table">
          <thead>
            <tr>
              <th>#</th>
              <th>{{ __('admin.brands.logo') }}</th>
              <th>{{ __('admin.brands.name_en') }}</th>
              <th>{{ __('admin.brands.name_ar') }}</th>
              <th>{{ __('admin.brands.status') }}</th>
              <th>{{ __('admin.brands.sort_order') }}</th>
              <th>{{ __('admin.actions') }}</th>
            </tr>
          </thead>
          <tbody>
            @foreach($brands as $b)
              <tr>
                <td>{{ $b->id }}</td>
                <td><img src="{{ asset($b->logo ?? 'images/brand-placeholder.png') }}" class="logo-thumb" alt="{{ $b->name_en }}"></td>
                <td>{{ $b->name_en }}</td>
                <td>{{ $b->name_ar }}</td>
                <td>
                  @if($b->status === 'active')
                    <span class="badge-active small">{{ __('admin.brands.active') }}</span>
                  @else
                    <span class="badge-inactive small">{{ __('admin.brands.inactive') }}</span>
                  @endif
                </td>
                <td>{{ $b->sort_order }}</td>
                <td>
                  <div class="actions">
                    <a href="{{ route('admin.brands.edit', $b->id) }}" class="btn btn-ghost btn-sm">{{ __('admin.brands.edit') }}</a>

                    <x-admin.status-toggle
                      :action="route('admin.brands.toggle', $b->id)"
                      :status="$b->status"
                      size="sm"
                      :confirmText="__('admin.brands.confirm_toggle')"
                    />

                    <form action="{{ route('admin.brands.destroy', $b->id) }}" method="POST" style="display:inline">
                      @csrf
                      @method('DELETE')
                      <button type="button" class="btn btn-danger btn-sm js-confirm" data-confirm="{{ __('admin.brands.confirm_delete') }}">{{ __('admin.delete') }}</button>
                    </form>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>

        <div class="pagination-wrap">
          {{ $brands->appends(request()->query())->links() }}
        </div>
      @else
        <div style="padding:2.5rem;text-align:center">
          <div class="h2">{{ __('admin.brands.empty') }}</div>
          <p class="p">{{ __('admin.brands.empty') }}</p>
        </div>
      @endif
    </div>
  </div>
@endsection
