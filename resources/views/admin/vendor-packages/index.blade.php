@extends('admin.layouts.app')

@section('content')
  <div class="card">
    <div class="toolbar">
      <div class="title-wrap">
        <h1 class="h1">{{ __('admin.vendor_packages.title') }}</h1>
        <div class="small p">{{ __('admin.vendor_packages.subtitle') ?? '' }}</div>
      </div>
      <div class="actions">
        <a href="{{ route('admin.vendor-packages.create') }}" class="btn btn-gold">{{ __('admin.vendor_packages.add') }}</a>
      </div>
    </div>

    <x-admin.table-filters
      :action="route('admin.vendor-packages.index')"
      :resetUrl="route('admin.vendor-packages.index')"
      :showStatus="true"
      :showPerPage="true"
      searchPlaceholder="{{ __('admin.vendor_packages.search_placeholder') }}"
    />

    <div class="mt-6 card table-wrap">
      @if($packages->count())
        <table class="table">
          <thead>
            <tr>
              <th>#</th>
              <th>{{ __('admin.vendor_packages.key') }}</th>
              <th>{{ __('admin.vendor_packages.name') }}</th>
              <th>{{ __('admin.vendor_packages.monthly_price') }}</th>
              <th>{{ __('admin.vendor_packages.yearly_price') }}</th>
              <th>{{ __('admin.vendor_packages.status') }}</th>
              <th>{{ __('admin.vendor_packages.sort_order') }}</th>
              <th>{{ __('admin.actions') }}</th>
            </tr>
          </thead>
          <tbody>
            @foreach($packages as $p)
              <tr>
                <td>{{ $p->id }}</td>
                <td>{{ $p->key }}</td>
                <td>{{ $p->getName() }}</td>
                <td>{{ number_format($p->monthly_price / 100, 2) }} {{ $p->currency }}</td>
                <td>{{ number_format($p->yearly_price / 100, 2) }} {{ $p->currency }}</td>
                <td>
                  @if($p->status === 'active')
                    <span class="badge-active small">{{ __('admin.vendor_packages.active') }}</span>
                  @else
                    <span class="badge-inactive small">{{ __('admin.vendor_packages.inactive') }}</span>
                  @endif
                </td>
                <td>{{ $p->sort_order }}</td>
                <td>
                  <div class="actions">
                    <a href="{{ route('admin.vendor-packages.edit', $p->id) }}" class="btn btn-ghost btn-sm">{{ __('admin.vendor_packages.edit') }}</a>

                    <form action="{{ route('admin.vendor-packages.toggle', $p->id) }}" method="POST" style="display:inline">
                      @csrf
                      @method('PATCH')
                      <button type="button" class="btn btn-success btn-sm js-confirm" data-confirm="{{ __('admin.vendor_packages.confirm_toggle') }}">{{ __('admin.vendor_packages.toggle') ?? __('admin.update') }}</button>
                    </form>

                    <form action="{{ route('admin.vendor-packages.destroy', $p->id) }}" method="POST" style="display:inline">
                      @csrf
                      @method('DELETE')
                      <button type="button" class="btn btn-danger btn-sm js-confirm" data-confirm="{{ __('admin.vendor_packages.confirm_delete') }}">{{ __('admin.delete') }}</button>
                    </form>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>

        <div class="pagination-wrap">
          {{ $packages->appends(request()->query())->links() }}
        </div>
      @else
        <div style="padding:2.5rem;text-align:center">
          <div class="h2">{{ __('admin.vendor_packages.empty') }}</div>
          <p class="p">{{ __('admin.vendor_packages.empty') }}</p>
        </div>
      @endif
    </div>
  </div>
@endsection
