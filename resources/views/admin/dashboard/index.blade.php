@extends('admin.layouts.app')

@section('content')
  <div class="grid grid-4 gap-6">
    <div class="card">
      <div class="small">{{ __('admin.sidebar.users') }}</div>
      <div class="h1" style="margin-top:.35rem;">{{ $stats['users'] }}</div>
    </div>

    <div class="card">
      <div class="small">{{ __('admin.sidebar.vendors') }}</div>
      <div class="h1" style="margin-top:.35rem;">{{ $stats['vendors'] }}</div>
    </div>

    <div class="card">
      <div class="small">{{ __('admin.sidebar.orders') }}</div>
      <div class="h1" style="margin-top:.35rem;">{{ $stats['orders_today'] }}</div>
    </div>

    <div class="card">
      <div class="small">{{ __('admin.dashboard.revenue') ?? 'Revenue' }}</div>
      <div class="h1" style="margin-top:.35rem;">—</div>
    </div>
  </div>

  <div class="mt-6 card">
    <h3 class="h2">{{ __('admin.dashboard.title') }}</h3>
    <p class="p">{{ __('admin.dashboard.subtitle') ?? 'Latest activity (placeholder)' }}</p>

    <div style="margin-top:1rem;">
      <table class="table">
        <thead>
          <tr>
            <th>{{ __('admin.dashboard.table.event') ?? 'Event' }}</th>
            <th>{{ __('admin.dashboard.table.user') ?? 'User' }}</th>
            <th>{{ __('admin.dashboard.table.time') ?? 'Time' }}</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>{{ __('admin.dashboard.events.user_registered') ?? 'New user registered' }}</td>
            <td>محمد</td>
            <td>{{ __('admin.dashboard.events.hours_ago_2') ?? '2 hours ago' }}</td>
          </tr>
          <tr>
            <td>{{ __('admin.dashboard.events.order_created') ?? 'Order created' }}</td>
            <td>علي</td>
            <td>{{ __('admin.dashboard.events.hours_ago_3') ?? '3 hours ago' }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
@endsection
