@extends('admin.layouts.app')

@section('content')
  <div class="card">
    <div class="toolbar">
      <div class="title-wrap">
        <h1 class="h1">{{ __('admin.vendors') ?? 'Vendors' }}</h1>
        <div class="small p">{{ __('admin.sidebar.vendors') }}</div>
      </div>
    </div>

    <x-admin.table-filters
      :action="route('admin.vendors.index')"
      :resetUrl="route('admin.vendors.index')"
      :showPerPage="true"
      searchPlaceholder="{{ __('admin.search') }}"
    />

    <div class="mt-6 card table-wrap">
      @if($vendors->count())
        <table class="table">
          <thead>
            <tr>
              <th>#</th>
              <th>{{ __('admin.users.name') ?? 'Name' }}</th>
              <th>{{ __('admin.users.email') ?? 'Email' }}</th>
              <th>{{ __('admin.users.phone') ?? 'Phone' }}</th>
              <th>{{ __('admin.vendor_package_assign.current') ?? 'Package' }}</th>
              <th>{{ __('admin.actions') }}</th>
            </tr>
          </thead>
          <tbody>
            @foreach($vendors as $v)
              <tr>
                <td>{{ $v->id }}</td>
                <td>
                  {{ $v->name }}
                  <div class="small p" style="color:var(--muted)">{{ $v->created_at->format('Y-m-d') }}</div>
                </td>
                <td>{{ $v->email }}</td>
                <td>{{ $v->phone }}</td>
                <td>
                  {{ optional(optional($v->activePackageAssignment)->package)->getName() ?? __('admin.vendor_package_assign.not_assigned') }}
                </td>
                <td>
                  <div class="actions">
                    <a href="{{ route('admin.vendors.show', $v->id) }}" class="btn btn-ghost btn-sm">{{ __('admin.view') ?? 'View' }}</a>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>

        <div class="pagination-wrap">
          {{ $vendors->appends(request()->query())->links() }}
        </div>
      @else
        <div style="padding:2.5rem;text-align:center">
          <div class="h2">{{ __('admin.vendors.empty') ?? 'No vendors found' }}</div>
          <p class="p">{{ __('admin.vendors.empty') ?? 'No vendors found' }}</p>
        </div>
      @endif
    </div>
  </div>
@endsection
