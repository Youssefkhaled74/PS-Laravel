@extends('admin.layouts.app')

@section('content')
  <div class="card">
    <div class="toolbar">
      <div class="title-wrap">
        <h1 class="h1">{{ __('admin.categories.title') }}</h1>
        <div class="small p">{{ __('admin.categories.subtitle') }}</div>
      </div>

      <div class="actions">
        <a href="{{ route('admin.categories.create') }}" class="btn btn-gold">{{ __('admin.categories.add') }}</a>
      </div>
    </div>

    <x-admin.table-filters
      :action="route('admin.categories.index')"
      :resetUrl="route('admin.categories.index')"
      :showStatus="true"
      :showPerPage="true"
      searchPlaceholder="{{ __('admin.categories.search_placeholder') }}"
    />

    <div class="mt-6 card table-wrap">
      @if($categories->count())
        <table class="table">
          <thead>
            <tr>
              <th>#</th>
              <th>{{ __('admin.categories.name_en') }}</th>
              <th>{{ __('admin.categories.name_ar') }}</th>
              <th>{{ __('admin.categories.status') }}</th>
              <th>{{ __('admin.categories.sort_order') }}</th>
              <th>{{ __('admin.actions') }}</th>
            </tr>
          </thead>
          <tbody>
            @foreach($categories as $cat)
              <tr>
                <td>{{ $cat->id }}</td>
                <td>
                  {{ $cat->name_en }}
                  <div class="small p" style="color:var(--muted)">{{ $cat->slug }}</div>
                </td>
                <td>{{ $cat->name_ar }}</td>
                <td>
                  @if($cat->status === 'active')
                    <span class="badge-active small">{{ __('admin.categories.active') }}</span>
                  @else
                    <span class="badge-inactive small">{{ __('admin.categories.inactive') }}</span>
                  @endif
                </td>
                <td>{{ $cat->sort_order }}</td>
                <td>
                  <div class="actions">
                    <a href="{{ route('admin.categories.edit', $cat->id) }}" class="btn btn-ghost btn-sm">{{ __('admin.categories.edit') }}</a>

                    <form action="{{ route('admin.categories.toggle', $cat->id) }}" method="POST" style="display:inline">
                      @csrf
                      @method('PATCH')
                      <button type="button" class="btn btn-success btn-sm js-confirm" data-confirm="{{ __('admin.categories.confirm_toggle') }}">{{ __('admin.categories.toggle') }}</button>
                    </form>

                    <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" style="display:inline">
                      @csrf
                      @method('DELETE')
                      <button type="button" class="btn btn-danger btn-sm js-confirm" data-confirm="{{ __('admin.categories.confirm_delete') }}">{{ __('admin.delete') }}</button>
                    </form>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>

        <div class="pagination-wrap">
          {{ $categories->appends(request()->query())->links() }}
        </div>
      @else
        <div style="padding:2.5rem;text-align:center">
          <div class="h2">{{ __('admin.categories.empty') }}</div>
          <p class="p">{{ __('admin.categories.empty') }}</p>
        </div>
      @endif
    </div>
  </div>
@endsection
