@extends('admin.layouts.app')

@section('content')
  <div class="page-header">
    <div style="display:flex;align-items:center;gap:1rem">
      <a href="{{ route('admin.vendors.index') }}" class="btn btn-ghost">← {{ __('admin.back') }}</a>
      <div>
        <div class="h1">{{ $vendor->name }}</div>
        <div class="small">{{ $vendor->email }} · {{ $vendor->phone }}</div>
      </div>
    </div>

    <div class="actions">
      <a href="#account-edit" class="btn btn-ghost">{{ __('admin.edit') }}</a>
      <x-admin.status-toggle :action="route('admin.vendors.toggle', $vendor->id)" :status="$vendor->status" size="sm" />
      <a href="#package-assign" class="btn btn-gold">{{ __('admin.vendor_package_assign.change') }}</a>
    </div>
  </div>

  <div class="vendor-grid">
    <div>
      <x-admin.info-card title="{{ __('admin.view') }}" subtitle="{{ __('admin.sidebar.vendors') }}">
        <div style="display:flex;gap:1rem;align-items:center">
          <div class="vendor-avatar">
            @if($vendor->avatar)
              <img src="{{ asset($vendor->avatar) }}" class="avatar-img" alt="avatar">
            @else
              <div class="avatar-placeholder">👤</div>
            @endif
          </div>
          <div>
            <div class="h2">{{ $vendor->name }}</div>
            <div class="small">{{ $vendor->email }}</div>
            <div class="small">{{ $vendor->phone }}</div>
            <div class="small">
              {{ __('admin.status') }}: <strong>{{ \Illuminate\Support\Facades\Lang::has('admin.statuses.' . $vendor->status) ? __('admin.statuses.' . $vendor->status) : ucfirst($vendor->status) }}</strong>
            </div>
            <div style="margin-top:.4rem">
              <form action="{{ route('admin.vendors.toggle', $vendor->id) }}" method="POST" class="inline-form js-confirm" data-confirm="{{ __('admin.status_toggle_confirm') }}">
                @csrf
                @method('PATCH')
                <label class="label small">Change status</label>
                <div style="display:flex;gap:.5rem;align-items:center">
                  <select name="status" class="input">
                    <option value="active" {{ $vendor->status === 'active' ? 'selected' : '' }}>{{ __('admin.statuses.active') }}</option>
                    <option value="inactive" {{ $vendor->status === 'inactive' ? 'selected' : '' }}>{{ __('admin.statuses.inactive') }}</option>
                    <option value="pending" {{ $vendor->status === 'pending' ? 'selected' : '' }}>{{ __('admin.statuses.pending') }}</option>
                    <option value="suspended" {{ $vendor->status === 'suspended' ? 'selected' : '' }}>{{ __('admin.statuses.suspended') }}</option>
                  </select>
                  <button class="btn btn-ghost" type="submit">{{ __('admin.status_toggle') ?? 'Change' }}</button>
                </div>
              </form>
            </div>
            <div class="small">{{ __('admin.created_at') ?? 'Created at' }}: {{ $vendor->created_at->format('Y-m-d') }}</div>
          </div>
        </div>

        <div style="margin-top:1rem">
          <form id="account-edit-form" action="{{ route('admin.vendors.updateAccount', $vendor->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <div style="display:flex;flex-direction:column;gap:.6rem">
              <label class="label">{{ __('admin.users.name') }}</label>
              <input name="name" class="input" value="{{ $vendor->name }}">

              <label class="label">{{ __('admin.users.email') }}</label>
              <input name="email" class="input" value="{{ $vendor->email }}">

              <label class="label">{{ __('admin.users.phone') }}</label>
              <input name="phone" class="input" value="{{ $vendor->phone }}">

              <label class="label">{{ __('admin.banks.bank') ?? 'WhatsApp' }}</label>
              <input name="whatsapp_phone" class="input" value="{{ $vendor->whatsapp_phone }}">

              <label class="label">{{ __('admin.bio') ?? 'Bio' }}</label>
              <textarea name="bio" class="input">{{ $vendor->bio }}</textarea>

              <label class="label">{{ __('admin.avatar') ?? 'Avatar' }}</label>
              <x-admin.upload-preview
                name="avatar"
                label="{{ __('admin.avatar') }}"
                currentUrl="{{ $vendor->avatar ? asset($vendor->avatar) : '' }}"
                accept="image/png,image/jpeg,image/webp"
                hint="{{ __('admin.upload_hint_image') }}"
                variant="circle"
                size="md"
              />

              <div style="display:flex;gap:.6rem;justify-content:flex-end;margin-top:.5rem">
                <button class="btn btn-ghost">{{ __('admin.cancel') }}</button>
                <button type="submit" class="btn btn-gold">{{ __('admin.save') }}</button>
              </div>
            </div>
          </form>
        </div>
      </x-admin.info-card>

      <x-admin.info-card title="{{ __('admin.vendor_package_assign.title') }}">
        @if($vendor->activePackageAssignment)
          <div class="kv-list">
            <div class="kv-row"><dt class="kv-label">{{ __('admin.vendor_package_assign.current') }}</dt><dd class="kv-value">{{ $vendor->activePackageAssignment->vendor_package_id }}</dd></div>
            <div class="kv-row"><dt class="kv-label">{{ __('admin.vendor_package_assign.starts_at') }}</dt><dd class="kv-value">{{ optional($vendor->activePackageAssignment->starts_at)->format('Y-m-d') }}</dd></div>
            <div class="kv-row"><dt class="kv-label">{{ __('admin.vendor_package_assign.ends_at') }}</dt><dd class="kv-value">{{ optional($vendor->activePackageAssignment->ends_at)->format('Y-m-d') }}</dd></div>
          </div>
        @else
          <div class="p">{{ __('admin.vendor_package_assign.not_assigned') }}</div>
        @endif

        <div style="margin-top:1rem">
          <form action="{{ route('admin.vendors.package.update', $vendor->id) }}" method="POST">
            @csrf
            <div style="display:flex;gap:.6rem;align-items:center">
              <select name="vendor_package_id" class="input">
                <option value="">{{ __('admin.vendor_package_assign.select_package') }}</option>
                @foreach(\App\Models\VendorPackage::where('status','active')->get() as $pkg)
                  <option value="{{ $pkg->id }}">{{ $pkg->getName() }}</option>
                @endforeach
              </select>
              <select name="billing_cycle" class="input">
                <option value="monthly">{{ __('admin.vendor_package_assign.monthly') }}</option>
                <option value="yearly">{{ __('admin.vendor_package_assign.yearly') }}</option>
              </select>
              <button class="btn btn-gold">{{ __('admin.save') }}</button>
            </div>
          </form>
        </div>
      </x-admin.info-card>
    </div>

    <div>
      <x-admin.info-card title="{{ __('admin.vendor_package_assign.current') ?? 'Business Profile' }}">
        @if($vendor->businessProfile)
          <x-admin.kv-list :items="[
            __('admin.vendor_business.commercial_name') => $vendor->businessProfile->commercial_name,
            __('admin.vendor_business.id_number') => $vendor->businessProfile->id_number,
            __('admin.vendor_business.commercial_register_number') => $vendor->businessProfile->commercial_register_number,
            __('admin.banks.bank') => optional($vendor->businessProfile->bank)->name_en ?? '-',
            __('admin.vendor_business.bank_account_number') => $vendor->businessProfile->bank_account_number,
          ]" />
        @else
          <div class="p">{{ __('admin.vendor_business.empty') ?? 'No business profile' }}</div>
        @endif

        <div style="margin-top:1rem">
          <form action="{{ route('admin.vendors.updateBusiness', $vendor->id) }}" method="POST">
            @csrf
            @method('PATCH')
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.6rem">
              <input name="commercial_name" class="input" placeholder="{{ __('admin.vendor_business.commercial_name') }}" value="{{ optional($vendor->businessProfile)->commercial_name }}">
              <input name="id_number" class="input" placeholder="{{ __('admin.vendor_business.id_number') }}" value="{{ optional($vendor->businessProfile)->id_number }}">
              <input name="commercial_register_number" class="input" placeholder="{{ __('admin.vendor_business.commercial_register_number') }}" value="{{ optional($vendor->businessProfile)->commercial_register_number }}">
              <select name="bank_id" class="input">
                <option value="">{{ __('admin.select') ?? 'Select bank' }}</option>
                @foreach(\App\Models\Bank::all() as $bank)
                  <option value="{{ $bank->id }}" {{ optional($vendor->businessProfile)->bank_id == $bank->id ? 'selected' : '' }}>{{ $bank->name_en }}</option>
                @endforeach
              </select>
              <input name="bank_account_number" class="input" placeholder="{{ __('admin.vendor_business.bank_account_number') }}" value="{{ optional($vendor->businessProfile)->bank_account_number }}">
            </div>
            <div style="display:flex;justify-content:flex-end;margin-top:.6rem">
              <button class="btn btn-ghost">{{ __('admin.cancel') }}</button>
              <button type="submit" class="btn btn-gold">{{ __('admin.save') }}</button>
            </div>
          </form>
        </div>
      </x-admin.info-card>

      <x-admin.info-card title="{{ __('admin.documents') }}">
        <div style="display:grid;gap:.6rem">
          @php $docs = $vendor->documents->keyBy('type'); @endphp
          <x-admin.file-tile title="ID Card" :url="optional($docs->get('id_card'))->file_path" inputName="id_card" />
          <x-admin.file-tile title="Commercial Register" :url="optional($docs->get('commercial_register'))->file_path" inputName="commercial_register" />
          <x-admin.file-tile title="Freelance Document" :url="optional($docs->get('freelance_doc'))->file_path" inputName="freelance_doc" />

          <form action="{{ route('admin.vendors.updateDocuments', $vendor->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="display:flex;gap:.6rem;justify-content:flex-end">
              <button class="btn btn-ghost">{{ __('admin.cancel') }}</button>
              <button type="submit" class="btn btn-gold">{{ __('admin.save') }}</button>
            </div>
          </form>
        </div>
      </x-admin.info-card>
    </div>
  </div>

  <div class="mt-6">
    <x-admin.info-card title="{{ __('admin.extra_data') ?? 'Extra Data' }}">
      <div class="grid" style="grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1rem">
        <div class="card-soft">
          <h4 class="h3">{{ __('admin.addresses') ?? 'Addresses' }}</h4>
          @if(optional($vendor->addresses)->count())
            @foreach($vendor->addresses as $addr)
              <div class="p">{{ $addr->street }} - {{ $addr->city }}</div>
            @endforeach
          @else
            <div class="p">No addresses</div>
          @endif
        </div>

        <div class="card-soft">
          <h4 class="h3">{{ __('admin.payment_methods') ?? 'Payment' }}</h4>
          @if(optional($vendor->paymentSelections)->count())
            @foreach($vendor->paymentSelections as $ps)
              <div class="p">{{ $ps->method }} - {{ $ps->status }}</div>
            @endforeach
          @else
            <div class="p">No payment method</div>
          @endif
        </div>
      </div>
    </x-admin.info-card>
  </div>
@endsection
