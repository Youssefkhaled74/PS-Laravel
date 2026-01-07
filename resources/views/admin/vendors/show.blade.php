@extends('admin.layouts.app')

@section('content')
  <div class="card">
    <div class="toolbar">
      <div class="title-wrap">
        <h1 class="h1">{{ $vendor->name ?? __('admin.vendors') }}</h1>
        <div class="small p">{{ __('admin.sidebar.vendors') }}</div>
      </div>
      <div class="actions">
        <a href="{{ route('admin.vendors.index') }}" class="btn btn-ghost">{{ __('admin.back') ?? 'Back' }}</a>
      </div>
    </div>

    <div class="mt-6 grid" style="grid-template-columns:1fr 1fr; gap:1rem">
      <div class="card">
        <h3 class="h3">{{ __('admin.view') }}</h3>
        <div class="p"><strong>{{ __('admin.users.name') ?? 'Name' }}:</strong> {{ $vendor->name }}</div>
        <div class="p"><strong>{{ __('admin.users.email') ?? 'Email' }}:</strong> {{ $vendor->email }}</div>
        <div class="p"><strong>{{ __('admin.users.phone') ?? 'Phone' }}:</strong> {{ $vendor->phone }}</div>
        <div class="p"><strong>Status:</strong> {{ $vendor->status }}</div>
      </div>

      <div class="card">
        <h3 class="h3">{{ __('admin.vendor_package_assign.title') ?? 'Package' }}</h3>
        @if($vendor->activePackageAssignment)
          <div class="p">{{ $vendor->activePackageAssignment->vendor_package_id }} - {{ $vendor->activePackageAssignment->status }}</div>
        @else
          <div class="p">{{ __('admin.vendor_package_assign.not_assigned') }}</div>
        @endif
      </div>
    </div>

    <div class="mt-6 card">
      <h3 class="h3">{{ __('admin.vendor_package_assign.current') ?? 'Business Profile' }}</h3>
      @if($vendor->businessProfile)
        <div class="p"><strong>{{ __('admin.vendor_package_assign.select_package') ?? 'Commercial Name' }}:</strong> {{ $vendor->businessProfile->commercial_name }}</div>
        <div class="p"><strong>ID:</strong> {{ $vendor->businessProfile->id_number }}</div>
        <div class="p"><strong>Bank:</strong> {{ optional($vendor->businessProfile->bank)->name_en ?? '' }}</div>
      @else
        <div class="p">No business profile</div>
      @endif
    </div>
  </div>
@endsection
