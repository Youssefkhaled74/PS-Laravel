
@extends('admin.layouts.app')

@section('content')
  <div class="card">
    <div class="toolbar">
      <div class="title-wrap">
        <h1 class="h1">{{ __('admin.users.title') }}</h1>
        <div class="small p">{{ __('admin.users.subtitle') }}</div>
      </div>
    </div>

    <x-admin.table-filters
      :action="route('admin.users.index')"
      :resetUrl="route('admin.users.index')"
      :showPerPage="true"
      searchPlaceholder="{{ __('admin.search') }}"
    />

    <div class="mt-6 card table-wrap">
      @if($users->count())
        <table class="table">
          <thead>
            <tr>
              <th>#</th>
              <th>{{ __('admin.users.name') ?? 'Name' }}</th>
              <th>{{ __('admin.users.email') ?? 'Email' }}</th>
              <th>{{ __('admin.users.phone') ?? 'Phone' }}</th>
              <th>{{ __('admin.users.addresses') ?? 'Addresses' }}</th>
              <th>{{ __('admin.actions') }}</th>
            </tr>
          </thead>
          <tbody>
            @foreach($users as $u)
              <tr>
                <td>{{ $u->id }}</td>
                <td>
                  {{ $u->full_name }}
                  <div class="small p" style="color:var(--muted)">{{ $u->created_at->format('Y-m-d') }}</div>
                </td>
                <td>{{ $u->email }}</td>
                <td>{{ ($u->country_code ?? '') . ($u->phone ?? '') }}</td>
                <td>{{ $u->addresses_count ?? 0 }}</td>
                <td>
                  <div class="actions">
                    <a href="{{ route('admin.users.show', $u->id) }}" class="btn btn-ghost btn-sm">{{ __('admin.view') ?? 'View' }}</a>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>

        <div class="pagination-wrap">
          {{ $users->appends(request()->query())->links() }}
        </div>
      @else
        <div style="padding:2.5rem;text-align:center">
          <div class="h2">{{ __('admin.users.empty') ?? 'No users found' }}</div>
          <p class="p">{{ __('admin.users.empty') ?? 'No users found' }}</p>
        </div>
      @endif
    </div>
  </div>
@endsection
