@extends('admin.layouts.app')

@section('content')
  <div class="card">
    <div class="toolbar">
      <div class="title-wrap">
        <h1 class="h1">{{ __('admin.banks.title') }}</h1>
        <div class="small p">{{ __('admin.banks.subtitle') }}</div>
      </div>
      <div class="actions">
        <a href="{{ route('admin.banks.create') }}" class="btn btn-gold">{{ __('admin.banks.add') }}</a>
      </div>
    </div>

    <x-admin.table-filters
      :action="route('admin.banks.index')"
      :resetUrl="route('admin.banks.index')"
      :showStatus="true"
      :showPerPage="true"
      searchPlaceholder="{{ __('admin.banks.search_placeholder') }}"
    />

    <div class="mt-6 card table-wrap">
      @if($banks->count())
        <table class="table">
          <thead>
            <tr>
              <th>#</th>
              <th>{{ __('admin.banks.logo') }}</th>
              <th>{{ __('admin.banks.name_en') }}</th>
              <th>{{ __('admin.banks.name_ar') }}</th>
              <th>{{ __('admin.banks.status') }}</th>
              <th>{{ __('admin.banks.sort_order') }}</th>
              <th>{{ __('admin.actions') }}</th>
            </tr>
          </thead>
          <tbody>
            @foreach($banks as $b)
              <tr>
                <td>{{ $b->id }}</td>
                <td><img src="{{ asset($b->logo ?? 'images/brand-placeholder.png') }}" class="logo-thumb" alt="{{ $b->name_en }}"></td>
                <td>{{ $b->name_en }}</td>
                <td>{{ $b->name_ar }}</td>
                <td>
                  @if($b->status === 'active')
                    <span class="badge-active small">{{ __('admin.banks.active') }}</span>
                  @else
                    <span class="badge-inactive small">{{ __('admin.banks.inactive') }}</span>
                  @endif
                </td>
                <td>{{ $b->sort_order }}</td>
                <td>
                  <div class="actions">
                    <a href="{{ route('admin.banks.edit', $b->id) }}" class="btn btn-ghost btn-sm">{{ __('admin.banks.edit') }}</a>

                    <form action="{{ route('admin.banks.toggle', $b->id) }}" method="POST" style="display:inline">
                      @csrf
                      @method('PATCH')
                      <button type="button" class="btn btn-success btn-sm js-confirm" data-confirm="{{ __('admin.banks.confirm_toggle') }}">{{ __('admin.banks.toggle') ?? __('admin.update') }}</button>
                    </form>

                    <form action="{{ route('admin.banks.destroy', $b->id) }}" method="POST" style="display:inline">
                      @csrf
                      @method('DELETE')
                      <button type="button" class="btn btn-danger btn-sm js-confirm" data-confirm="{{ __('admin.banks.confirm_delete') }}">{{ __('admin.delete') }}</button>
                    </form>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>

        <div class="pagination-wrap">
          {{ $banks->appends(request()->query())->links() }}
        </div>
      @else
        <div style="padding:2.5rem;text-align:center">
          <div class="h2">{{ __('admin.banks.empty') }}</div>
          <p class="p">{{ __('admin.banks.empty') }}</p>
        </div>
      @endif
    </div>
  </div>
@endsection
