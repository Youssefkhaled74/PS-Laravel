@extends('admin.layouts.app')

@section('content')
  {{-- =========================
      PAGE (Enhanced UI/UX)
  ========================== --}}
  <div class="vendor-page">
    {{-- Header --}}
    <div class="page-header page-header--sticky">
      <div class="page-header__left">
        <a href="{{ route('admin.vendors.index') }}" class="btn btn-ghost btn-back">← {{ __('admin.back') }}</a>

        <div class="page-title">
          <div class="h1">{{ $vendor->name }}</div>
          <div class="meta-line">
            <span class="meta-pill">{{ $vendor->email ?: '-' }}</span>
            <span class="meta-dot">•</span>
            <span class="meta-pill">{{ $vendor->phone ?: '-' }}</span>
          </div>
        </div>
      </div>

      <div class="page-header__right">
        <button type="button" class="btn btn-ghost js-toggle" data-target="#section-account">{{ __('admin.edit') }}</button>

        <x-admin.status-toggle :action="route('admin.vendors.toggle', $vendor->id)" :status="$vendor->status" size="sm" />

        <button type="button" class="btn btn-gold js-toggle" data-target="#section-package">
          {{ __('admin.vendor_package_assign.change') }}
        </button>
      </div>
    </div>

    {{-- Quick Summary Row --}}
    <div class="summary-row">
      <div class="summary-card">
        <div class="summary-card__icon">
          @if($vendor->avatar)
            <img src="{{ asset($vendor->avatar) }}" class="summary-avatar" alt="avatar">
          @else
            <div class="summary-avatar summary-avatar--placeholder">👤</div>
          @endif
        </div>
        <div class="summary-card__content">
          <div class="summary-name">{{ $vendor->name }}</div>
          <div class="summary-sub">{{ $vendor->email ?: '-' }}</div>

          <div class="summary-badges">
            @php
              $statusKey = 'admin.statuses.' . $vendor->status;
              $statusLabel = \Illuminate\Support\Facades\Lang::has($statusKey) ? __($statusKey) : ucfirst($vendor->status);
              $statusClass = match($vendor->status) {
                'active' => 'badge--success',
                'inactive' => 'badge--muted',
                'pending' => 'badge--warning',
                'suspended' => 'badge--danger',
                default => 'badge--neutral'
              };
            @endphp

            <span class="badge {{ $statusClass }}">
              <span class="badge-dot"></span>
              <span>{{ $statusLabel }}</span>
            </span>

            <span class="badge badge--neutral">
              <span class="badge-label">{{ __('admin.created_at') ?? 'Created at' }}:</span>
              <span>{{ $vendor->created_at?->format('Y-m-d') }}</span>
            </span>
          </div>
        </div>

        <div class="summary-card__actions">
          <a href="#section-status" class="btn btn-ghost btn-sm js-scroll">{{ __('admin.status') }}</a>
          <a href="#section-docs" class="btn btn-ghost btn-sm js-scroll">{{ __('admin.documents') }}</a>
          <a href="#section-extra" class="btn btn-ghost btn-sm js-scroll">{{ __('admin.extra_data') ?? 'Extra Data' }}</a>
        </div>
      </div>
    </div>

    {{-- Main Grid --}}
    <div class="vendor-grid">
      {{-- LEFT COLUMN --}}
      <div class="stack">
        {{-- Status --}}
        <x-admin.info-card title="{{ __('admin.status') }}" subtitle="{{ __('admin.sidebar.vendors') }}" id="section-status">
          <div class="section-head">
            <div class="section-head__title">
              <div class="h3">{{ __('admin.status') }}</div>
              <div class="small muted">{{ __('admin.status_toggle_confirm') }}</div>
            </div>
            <button class="btn btn-ghost btn-sm js-toggle" data-target="#status-form">
              {{ __('admin.edit') }}
            </button>
          </div>

          <div class="status-display">
            <span class="badge {{ $statusClass }}">
              <span class="badge-dot"></span>
              <span>{{ $statusLabel }}</span>
            </span>
          </div>

          <div id="status-form" class="collapse">
            <form action="{{ route('admin.vendors.toggle', $vendor->id) }}" method="POST" class="inline-form js-confirm" data-confirm="{{ __('admin.status_toggle_confirm') }}">
              @csrf
              @method('PATCH')

              <div class="form-row">
                <div class="field">
                  <label class="label small">{{ __('admin.status') }}</label>
                  <select name="status" class="input">
                    <option value="active" {{ $vendor->status === 'active' ? 'selected' : '' }}>{{ __('admin.statuses.active') }}</option>
                    <option value="inactive" {{ $vendor->status === 'inactive' ? 'selected' : '' }}>{{ __('admin.statuses.inactive') }}</option>
                    <option value="pending" {{ $vendor->status === 'pending' ? 'selected' : '' }}>{{ __('admin.statuses.pending') }}</option>
                    <option value="suspended" {{ $vendor->status === 'suspended' ? 'selected' : '' }}>{{ __('admin.statuses.suspended') }}</option>
                  </select>
                </div>

                <div class="form-actions">
                  <button class="btn btn-ghost" type="button" data-collapse-close="#status-form">{{ __('admin.cancel') }}</button>
                  <button class="btn btn-gold" type="submit">{{ __('admin.save') }}</button>
                </div>
              </div>
            </form>
          </div>
        </x-admin.info-card>

        {{-- Account --}}
        <x-admin.info-card title="{{ __('admin.view') }}" subtitle="{{ __('admin.users.name') }}" id="section-account">
          <div class="section-head">
            <div>
              <div class="h3">{{ __('admin.view') }}</div>
              <div class="small muted">{{ __('admin.users.name') }} / {{ __('admin.users.email') }} / {{ __('admin.users.phone') }}</div>
            </div>
            <button class="btn btn-ghost btn-sm js-toggle" data-target="#account-form">
              {{ __('admin.edit') }}
            </button>
          </div>

          <div class="kv-wrap">
            <div class="kv">
              <div class="kv__label">{{ __('admin.users.name') }}</div>
              <div class="kv__value">{{ $vendor->name }}</div>
            </div>
            <div class="kv">
              <div class="kv__label">{{ __('admin.users.email') }}</div>
              <div class="kv__value">{{ $vendor->email ?: '-' }}</div>
            </div>
            <div class="kv">
              <div class="kv__label">{{ __('admin.users.phone') }}</div>
              <div class="kv__value">{{ $vendor->phone ?: '-' }}</div>
            </div>
            <div class="kv">
              <div class="kv__label">{{ __('admin.banks.bank') ?? 'WhatsApp' }}</div>
              <div class="kv__value">{{ $vendor->whatsapp_phone ?: '-' }}</div>
            </div>
          </div>

          <div id="account-form" class="collapse">
            <form id="account-edit-form" action="{{ route('admin.vendors.updateAccount', $vendor->id) }}" method="POST" enctype="multipart/form-data">
              @csrf
              @method('PATCH')

              <div class="grid-2">
                <div class="field">
                  <label class="label">{{ __('admin.users.name') }}</label>
                  <input name="name" class="input" value="{{ $vendor->name }}">
                </div>

                <div class="field">
                  <label class="label">{{ __('admin.users.email') }}</label>
                  <input name="email" class="input" value="{{ $vendor->email }}">
                </div>

                <div class="field">
                  <label class="label">{{ __('admin.users.phone') }}</label>
                  <input name="phone" class="input" value="{{ $vendor->phone }}">
                </div>

                <div class="field">
                  <label class="label">{{ __('admin.banks.bank') ?? 'WhatsApp' }}</label>
                  <input name="whatsapp_phone" class="input" value="{{ $vendor->whatsapp_phone }}">
                </div>

                <div class="field grid-span-2">
                  <label class="label">{{ __('admin.bio') ?? 'Bio' }}</label>
                  <textarea name="bio" class="input" rows="3">{{ $vendor->bio }}</textarea>
                </div>

                <div class="field grid-span-2">
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
                </div>
              </div>

              <div class="form-actions">
                <button class="btn btn-ghost" type="button" data-collapse-close="#account-form">{{ __('admin.cancel') }}</button>
                <button type="submit" class="btn btn-gold">{{ __('admin.save') }}</button>
              </div>
            </form>
          </div>
        </x-admin.info-card>

        {{-- Package --}}
        <x-admin.info-card title="{{ __('admin.vendor_package_assign.title') }}" id="section-package">
          <div class="section-head">
            <div>
              <div class="h3">{{ __('admin.vendor_package_assign.title') }}</div>
              <div class="small muted">{{ __('admin.vendor_package_assign.current') }}</div>
            </div>
            <button class="btn btn-ghost btn-sm js-toggle" data-target="#package-form">
              {{ __('admin.vendor_package_assign.change') }}
            </button>
          </div>

          @if($vendor->activePackageAssignment)
            <div class="kv-wrap">
              <div class="kv">
                <div class="kv__label">{{ __('admin.vendor_package_assign.current') }}</div>
                <div class="kv__value">{{ $vendor->activePackageAssignment->vendor_package_id }}</div>
              </div>
              <div class="kv">
                <div class="kv__label">{{ __('admin.vendor_package_assign.starts_at') }}</div>
                <div class="kv__value">{{ optional($vendor->activePackageAssignment->starts_at)->format('Y-m-d') }}</div>
              </div>
              <div class="kv">
                <div class="kv__label">{{ __('admin.vendor_package_assign.ends_at') }}</div>
                <div class="kv__value">{{ optional($vendor->activePackageAssignment->ends_at)->format('Y-m-d') }}</div>
              </div>
            </div>
          @else
            <div class="empty-state">{{ __('admin.vendor_package_assign.not_assigned') }}</div>
          @endif

          <div id="package-form" class="collapse">
            <form action="{{ route('admin.vendors.package.update', $vendor->id) }}" method="POST">
              @csrf

              <div class="grid-2">
                <div class="field">
                  <label class="label">{{ __('admin.vendor_package_assign.select_package') }}</label>
                  <select name="vendor_package_id" class="input">
                    <option value="">{{ __('admin.vendor_package_assign.select_package') }}</option>
                    @foreach(\App\Models\VendorPackage::where('status','active')->get() as $pkg)
                      <option value="{{ $pkg->id }}">{{ $pkg->getName() }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="field">
                  <label class="label">{{ __('admin.vendor_package_assign.billing_cycle') ?? 'Billing cycle' }}</label>
                  <select name="billing_cycle" class="input">
                    <option value="monthly">{{ __('admin.vendor_package_assign.monthly') }}</option>
                    <option value="yearly">{{ __('admin.vendor_package_assign.yearly') }}</option>
                  </select>
                </div>
              </div>

              <div class="form-actions">
                <button class="btn btn-ghost" type="button" data-collapse-close="#package-form">{{ __('admin.cancel') }}</button>
                <button class="btn btn-gold">{{ __('admin.save') }}</button>
              </div>
            </form>
          </div>
        </x-admin.info-card>
      </div>

      {{-- RIGHT COLUMN --}}
      <div class="stack">
        {{-- Business Profile --}}
        <x-admin.info-card title="{{ __('admin.vendor_package_assign.current') ?? 'Business Profile' }}">
          <div class="section-head">
            <div>
              <div class="h3">{{ __('admin.vendor_package_assign.current') ?? 'Business Profile' }}</div>
              <div class="small muted">{{ __('admin.vendor_business.commercial_name') }}</div>
            </div>
            <button class="btn btn-ghost btn-sm js-toggle" data-target="#business-form">
              {{ __('admin.edit') }}
            </button>
          </div>

          @if($vendor->businessProfile)
            <x-admin.kv-list :items="[
              __('admin.vendor_business.commercial_name') => $vendor->businessProfile->commercial_name,
              __('admin.vendor_business.id_number') => $vendor->businessProfile->id_number,
              __('admin.vendor_business.commercial_register_number') => $vendor->businessProfile->commercial_register_number,
              __('admin.banks.bank') => optional($vendor->businessProfile->bank)->name_en ?? '-',
              __('admin.vendor_business.bank_account_number') => $vendor->businessProfile->bank_account_number,
            ]" />
          @else
            <div class="empty-state">{{ __('admin.vendor_business.empty') ?? 'No business profile' }}</div>
          @endif

          <div id="business-form" class="collapse">
            <form action="{{ route('admin.vendors.updateBusiness', $vendor->id) }}" method="POST">
              @csrf
              @method('PATCH')

              <div class="grid-2">
                <div class="field">
                  <label class="label">{{ __('admin.vendor_business.commercial_name') }}</label>
                  <input name="commercial_name" class="input" value="{{ optional($vendor->businessProfile)->commercial_name }}">
                </div>

                <div class="field">
                  <label class="label">{{ __('admin.vendor_business.id_number') }}</label>
                  <input name="id_number" class="input" value="{{ optional($vendor->businessProfile)->id_number }}">
                </div>

                <div class="field">
                  <label class="label">{{ __('admin.vendor_business.commercial_register_number') }}</label>
                  <input name="commercial_register_number" class="input" value="{{ optional($vendor->businessProfile)->commercial_register_number }}">
                </div>

                <div class="field">
                  <label class="label">{{ __('admin.banks.bank') }}</label>
                  <select name="bank_id" class="input">
                    <option value="">{{ __('admin.select') ?? 'Select bank' }}</option>
                    @foreach(\App\Models\Bank::all() as $bank)
                      <option value="{{ $bank->id }}" {{ optional($vendor->businessProfile)->bank_id == $bank->id ? 'selected' : '' }}>{{ $bank->name_en }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="field grid-span-2">
                  <label class="label">{{ __('admin.vendor_business.bank_account_number') }}</label>
                  <input name="bank_account_number" class="input" value="{{ optional($vendor->businessProfile)->bank_account_number }}">
                </div>
              </div>

              <div class="form-actions">
                <button class="btn btn-ghost" type="button" data-collapse-close="#business-form">{{ __('admin.cancel') }}</button>
                <button type="submit" class="btn btn-gold">{{ __('admin.save') }}</button>
              </div>
            </form>
          </div>
        </x-admin.info-card>

        {{-- Documents --}}
        <x-admin.info-card title="{{ __('admin.documents') }}" id="section-docs">
          <div class="section-head">
            <div>
              <div class="h3">{{ __('admin.documents') }}</div>
              <div class="small muted">{{ __('admin.upload_hint_image') ?? 'Upload & review documents' }}</div>
            </div>
          </div>

          <div class="docs-grid">
            @php $docs = $vendor->documents->keyBy('type'); @endphp

            <x-admin.file-tile title="ID Card" :url="optional($docs->get('id_card'))->file_path" inputName="id_card" />
            <x-admin.file-tile title="Commercial Register" :url="optional($docs->get('commercial_register'))->file_path" inputName="commercial_register" />
            <x-admin.file-tile title="Freelance Document" :url="optional($docs->get('freelance_doc'))->file_path" inputName="freelance_doc" />
          </div>

          <form action="{{ route('admin.vendors.updateDocuments', $vendor->id) }}" method="POST" enctype="multipart/form-data" class="mt-2">
            @csrf
            <div class="form-actions">
              <button class="btn btn-ghost" type="reset">{{ __('admin.cancel') }}</button>
              <button type="submit" class="btn btn-gold">{{ __('admin.save') }}</button>
            </div>
          </form>
        </x-admin.info-card>

        {{-- Extra Data --}}
        <x-admin.info-card title="{{ __('admin.extra_data') ?? 'Extra Data' }}" id="section-extra">
          <div class="extra-grid">
            <div class="card-soft">
              <div class="card-soft__head">
                <h4 class="h3">{{ __('admin.addresses') ?? 'Addresses' }}</h4>
                <span class="badge badge--neutral">{{ optional($vendor->addresses)->count() ?? 0 }}</span>
              </div>

              @if(optional($vendor->addresses)->count())
                <div class="list">
                  @foreach($vendor->addresses as $addr)
                    <div class="list-row">
                      <div class="list-title">{{ $addr->street ?: '-' }}</div>
                      <div class="list-sub muted">{{ $addr->city ?: '-' }}</div>
                    </div>
                  @endforeach
                </div>
              @else
                <div class="empty-state">No addresses</div>
              @endif
            </div>

            <div class="card-soft">
              <div class="card-soft__head">
                <h4 class="h3">{{ __('admin.payment_methods') ?? 'Payment' }}</h4>
                <span class="badge badge--neutral">{{ optional($vendor->paymentSelections)->count() ?? 0 }}</span>
              </div>

              @if(optional($vendor->paymentSelections)->count())
                <div class="list">
                  @foreach($vendor->paymentSelections as $ps)
                    <div class="list-row">
                      <div class="list-title">{{ $ps->method ?? $ps->payment_method ?? '-' }}</div>
                      <div class="list-sub muted">{{ $ps->status ?? '-' }}</div>
                    </div>
                  @endforeach
                </div>
              @else
                <div class="empty-state">No payment method</div>
              @endif
            </div>
          </div>
        </x-admin.info-card>
      </div>
    </div>
  </div>

  {{-- =========================
      Inline CSS (same page)
  ========================== --}}
  <style>
    .vendor-page { max-width: 1200px; margin: 0 auto; padding: 18px 14px 40px; }
    .page-header { display:flex; align-items:center; justify-content:space-between; gap:14px; padding: 12px 10px; border-radius: 14px; }
    .page-header--sticky { position: sticky; top: 10px; z-index: 20; backdrop-filter: blur(10px); background: rgba(0,0,0,.25); border: 1px solid rgba(255,255,255,.06); }
    .page-header__left { display:flex; align-items:center; gap: 12px; min-width: 0; }
    .page-header__right { display:flex; align-items:center; gap: 10px; flex-wrap: wrap; justify-content:flex-end; }

    .btn-back { white-space: nowrap; }
    .page-title { min-width: 0; }
    .meta-line { display:flex; align-items:center; gap:10px; margin-top: 4px; flex-wrap: wrap; }
    .meta-pill { font-size: 12px; opacity: .9; padding: 6px 10px; border-radius: 999px; background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.06); }
    .meta-dot { opacity: .5; }

    .summary-row { margin: 14px 0 18px; }
    .summary-card { display:flex; align-items:center; justify-content:space-between; gap:14px; padding: 14px; border-radius: 16px; background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.06); }
    .summary-card__icon { display:flex; align-items:center; justify-content:center; }
    .summary-avatar { width: 54px; height: 54px; border-radius: 14px; object-fit: cover; border: 1px solid rgba(255,255,255,.10); }
    .summary-avatar--placeholder { display:flex; align-items:center; justify-content:center; font-size: 24px; background: rgba(255,255,255,.06); }
    .summary-card__content { flex: 1; min-width: 0; }
    .summary-name { font-size: 16px; font-weight: 700; }
    .summary-sub { font-size: 12px; opacity: .8; margin-top: 2px; }
    .summary-badges { display:flex; align-items:center; gap:8px; margin-top: 8px; flex-wrap: wrap; }
    .summary-card__actions { display:flex; gap: 8px; flex-wrap: wrap; justify-content:flex-end; }

    .vendor-grid { display:grid; grid-template-columns: 1.05fr .95fr; gap: 14px; }
    .stack { display:flex; flex-direction:column; gap: 14px; }

    .section-head { display:flex; align-items:flex-start; justify-content:space-between; gap: 10px; margin-bottom: 10px; }
    .muted { opacity: .75; }
    .empty-state { padding: 12px; border-radius: 12px; background: rgba(255,255,255,.03); border: 1px dashed rgba(255,255,255,.08); opacity: .9; }

    .kv-wrap { display:grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 8px; }
    .kv { padding: 10px; border-radius: 12px; background: rgba(255,255,255,.03); border: 1px solid rgba(255,255,255,.06); }
    .kv__label { font-size: 12px; opacity: .75; }
    .kv__value { font-size: 13px; font-weight: 600; margin-top: 6px; word-break: break-word; }

    .badge { display:inline-flex; align-items:center; gap:8px; padding: 6px 10px; border-radius: 999px; font-size: 12px; border: 1px solid rgba(255,255,255,.10); background: rgba(255,255,255,.05); }
    .badge-dot { width: 8px; height: 8px; border-radius: 99px; background: rgba(255,255,255,.55); box-shadow: 0 0 0 3px rgba(255,255,255,.08); }
    .badge--success { background: rgba(34,197,94,.10); border-color: rgba(34,197,94,.25); }
    .badge--success .badge-dot { background: rgba(34,197,94,.85); box-shadow: 0 0 0 3px rgba(34,197,94,.18); }
    .badge--warning { background: rgba(245,158,11,.10); border-color: rgba(245,158,11,.25); }
    .badge--warning .badge-dot { background: rgba(245,158,11,.85); box-shadow: 0 0 0 3px rgba(245,158,11,.18); }
    .badge--danger { background: rgba(239,68,68,.10); border-color: rgba(239,68,68,.25); }
    .badge--danger .badge-dot { background: rgba(239,68,68,.85); box-shadow: 0 0 0 3px rgba(239,68,68,.18); }
    .badge--muted { background: rgba(148,163,184,.10); border-color: rgba(148,163,184,.22); }
    .badge--neutral { background: rgba(255,255,255,.05); border-color: rgba(255,255,255,.10); }
    .badge-label { opacity: .75; }

    .grid-2 { display:grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 10px; }
    .grid-span-2 { grid-column: 1 / -1; }
    .field .label { display:block; margin-bottom: 6px; }

    .form-row { display:flex; gap: 10px; align-items:flex-end; flex-wrap: wrap; margin-top: 10px; }
    .form-actions { display:flex; justify-content:flex-end; gap: 10px; margin-top: 12px; }

    .collapse { display:none; margin-top: 10px; padding-top: 10px; border-top: 1px solid rgba(255,255,255,.06); }
    .collapse.is-open { display:block; animation: fadeIn .18s ease-out; }
    @keyframes fadeIn { from { opacity: .2; transform: translateY(-2px); } to { opacity: 1; transform: translateY(0); } }

    .docs-grid { display:grid; grid-template-columns: repeat(3, minmax(0,1fr)); gap: 10px; }

    .extra-grid { display:grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    .card-soft { padding: 12px; border-radius: 14px; background: rgba(255,255,255,.03); border: 1px solid rgba(255,255,255,.06); }
    .card-soft__head { display:flex; align-items:center; justify-content:space-between; gap: 10px; margin-bottom: 10px; }

    .list { display:flex; flex-direction:column; gap: 8px; }
    .list-row { padding: 10px; border-radius: 12px; background: rgba(0,0,0,.18); border: 1px solid rgba(255,255,255,.06); }
    .list-title { font-weight: 700; font-size: 13px; }
    .list-sub { font-size: 12px; margin-top: 4px; }

    .btn-sm { padding: 8px 10px; font-size: 12px; }

    @media (max-width: 980px) {
      .vendor-grid { grid-template-columns: 1fr; }
      .docs-grid { grid-template-columns: 1fr; }
      .extra-grid { grid-template-columns: 1fr; }
      .kv-wrap { grid-template-columns: 1fr; }
      .summary-card { flex-direction: column; align-items: stretch; }
      .summary-card__actions { justify-content:flex-start; }
    }
  </style>

  {{-- =========================
      Inline JS (same page)
  ========================== --}}
  <script>
    (function () {
      // Toggle collapse sections
      document.querySelectorAll('.js-toggle').forEach(btn => {
        btn.addEventListener('click', () => {
          const target = btn.getAttribute('data-target');
          if (!target) return;
          const el = document.querySelector(target);
          if (!el) return;
          el.classList.toggle('is-open');
          if (el.classList.contains('is-open')) {
            el.scrollIntoView({ behavior: 'smooth', block: 'start' });
          }
        });
      });

      // Close collapse buttons
      document.querySelectorAll('[data-collapse-close]').forEach(btn => {
        btn.addEventListener('click', () => {
          const target = btn.getAttribute('data-collapse-close');
          const el = document.querySelector(target);
          if (el) el.classList.remove('is-open');
        });
      });

      // Smooth scroll anchors
      document.querySelectorAll('.js-scroll').forEach(a => {
        a.addEventListener('click', (e) => {
          const href = a.getAttribute('href');
          if (!href || !href.startsWith('#')) return;
          const el = document.querySelector(href);
          if (!el) return;
          e.preventDefault();
          el.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
      });
    })();
  </script>
@endsection
