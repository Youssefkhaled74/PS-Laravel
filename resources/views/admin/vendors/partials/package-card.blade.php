<div class="card mt-4">
  <div class="title-wrap">
    <h3 class="h3">{{ __('admin.vendor_package_assign.title') }}</h3>
  </div>

  <div class="p">
    @php $current = $vendor->activeVendorPackageAssignment; @endphp
    @if($current)
      <div><strong>{{ __('admin.vendor_package_assign.current') }}:</strong> {{ $current->package->getName() }} ({{ ucfirst($current->billing_cycle) }})</div>
      <div class="small p">{{ __('admin.vendor_package_assign.starts_at') }}: {{ $current->starts_at->format('Y-m-d') }}</div>
      <div class="small p">{{ __('admin.vendor_package_assign.ends_at') }}: {{ $current->ends_at?->format('Y-m-d') ?? '-' }}</div>
    @else
      <div>{{ __('admin.vendor_package_assign.not_assigned') }}</div>
    @endif
  </div>

  <div class="divider"></div>

  <form action="{{ route('admin.vendors.package.update', $vendor->id) }}" method="POST" class="p">
    @csrf
    <div class="form-group">
      <label class="label">{{ __('admin.vendor_package_assign.select_package') }}</label>
      <select name="vendor_package_id" class="input">
        @foreach(App\Models\VendorPackage::where('status','active')->orderBy('sort_order')->get() as $pkg)
          <option value="{{ $pkg->id }}">{{ $pkg->getName() }} - {{ number_format($pkg->monthly_price/100,2) }} {{ $pkg->currency }}</option>
        @endforeach
      </select>
    </div>

    <div class="grid grid-2 gap-6 mt-3">
      <div class="form-group">
        <label class="label">{{ __('admin.vendor_package_assign.billing_cycle') }}</label>
        <select name="billing_cycle" class="input">
          <option value="monthly">{{ __('admin.vendor_package_assign.monthly') }}</option>
          <option value="yearly">{{ __('admin.vendor_package_assign.yearly') }}</option>
        </select>
      </div>

      <div class="form-group">
        <label class="label">{{ __('admin.vendor_package_assign.starts_at') }}</label>
        <input type="date" name="starts_at" class="input" value="{{ now()->format('Y-m-d') }}">
      </div>
    </div>

    <div class="actions mt-3">
      <button class="btn btn-gold">{{ __('admin.vendor_package_assign.assign') }}</button>
    </div>
  </form>
</div>
