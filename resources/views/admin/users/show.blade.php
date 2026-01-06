@extends('admin.layouts.app')

@section('content')
  <div class="card">
    <div class="toolbar">
      <div class="title-wrap">
        <h1 class="h1">{{ $user->full_name ?? __('admin.users.title') }}</h1>
        <div class="small p">{{ __('admin.users.subtitle') }}</div>
      </div>
      <div class="actions">
        <a href="{{ route('admin.users.index') }}" class="btn btn-ghost">{{ __('admin.back') ?? 'Back' }}</a>
      </div>
    </div>

    <div class="mt-6 grid" style="grid-template-columns: 1fr 1fr; gap:1rem">
      <div class="card">
        <h3 class="h3">{{ __('admin.users.profile') ?? 'Profile' }}</h3>
        <div class="p"><strong>{{ __('admin.users.name') ?? 'Name' }}:</strong> {{ $user->full_name }}</div>
        <div class="p"><strong>{{ __('admin.users.email') ?? 'Email' }}:</strong> {{ $user->email }}</div>
        <div class="p"><strong>{{ __('admin.users.phone') ?? 'Phone' }}:</strong> {{ ($user->country_code ?? '') . ($user->phone ?? '') }}</div>
        <div class="p"><strong>{{ __('admin.users.joined') ?? 'Joined' }}:</strong> {{ $user->created_at->format('Y-m-d H:i') }}</div>
      </div>

      <div class="card">
        <h3 class="h3">{{ __('admin.users.addresses') ?? 'Addresses' }}</h3>
        @if($user->addresses->count())
          @foreach($user->addresses as $addr)
            <div style="padding:0.75rem;border-bottom:1px solid var(--muted)">
              <div><strong>{{ $addr->label ?? __('admin.users.address') ?? 'Address' }}</strong> @if($addr->is_default) <span class="badge-active small">{{ __('admin.default') ?? 'Default' }}</span> @endif</div>
              <div class="small p">{{ $addr->city }}, {{ $addr->country }}</div>
              <div class="p">{{ $addr->street }} {{ $addr->building_no ? '#'.$addr->building_no : '' }} {{ $addr->apartment_no ? ' Apt '.$addr->apartment_no : '' }}</div>
              <div class="small p">{{ $addr->phone }}</div>
            </div>
          @endforeach
        @else
          <div class="p">{{ __('admin.users.no_addresses') ?? 'No addresses available' }}</div>
        @endif
      </div>
    </div>
  </div>
@endsection
