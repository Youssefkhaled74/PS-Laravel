@extends('admin.layouts.app')

@section('content')
  <div class="card">
    <div class="toolbar">
      <div class="title-wrap">
        <h1 class="h1">{{ __('admin.otps.title') }}</h1>
        <div class="small p">{{ __('admin.otps.subtitle') }}</div>
      </div>
    </div>

    <x-admin.table-filters
      :action="route('admin.otps.index')"
      :resetUrl="route('admin.otps.index')"
      :showPerPage="true"
      searchPlaceholder="{{ __('admin.otps.search_placeholder') }}"
    >
      <div class="filters-grid">
        <div class="field">
          <label class="field-label">{{ __('admin.otps.purpose') }}</label>
          <div class="select"><select name="purpose" class="input">
            <option value="">{{ __('admin.all') }}</option>
            <option value="REGISTER_VERIFY" {{ ($filters['purpose'] ?? '')==='REGISTER_VERIFY' ? 'selected' : '' }}>REGISTER_VERIFY</option>
            <option value="PASSWORD_RESET" {{ ($filters['purpose'] ?? '')==='PASSWORD_RESET' ? 'selected' : '' }}>PASSWORD_RESET</option>
          </select></div>
        </div>

        <div class="field">
          <label class="field-label">{{ __('admin.otps.status') }}</label>
          <div class="select"><select name="status" class="input">
            <option value="">{{ __('admin.all') }}</option>
            <option value="active" {{ ($filters['status'] ?? '')==='active' ? 'selected' : '' }}>{{ __('admin.otps.active') }}</option>
            <option value="used" {{ ($filters['status'] ?? '')==='used' ? 'selected' : '' }}>{{ __('admin.otps.used') }}</option>
            <option value="expired" {{ ($filters['status'] ?? '')==='expired' ? 'selected' : '' }}>{{ __('admin.otps.expired') }}</option>
            <option value="revoked" {{ ($filters['status'] ?? '')==='revoked' ? 'selected' : '' }}>{{ __('admin.otps.revoked') }}</option>
          </select></div>
        </div>
      </div>
    </x-admin.table-filters>

    <div class="mt-6 card table-wrap">
      @if($otps->count())
        <table class="table">
          <thead>
            <tr>
              <th>#</th>
              <th>{{ __('admin.otps.user') ?? 'User' }}</th>
              <th>{{ __('admin.otps.contact') ?? 'Contact' }}</th>
              <th>{{ __('admin.otps.purpose') }}</th>
              <th>{{ __('admin.otps.channel') }}</th>
              <th>{{ __('admin.otps.masked') }}</th>
              <th>{{ __('admin.otps.status') }}</th>
              <th>{{ __('admin.otps.expires_at') }}</th>
              <th>{{ __('admin.created_at') }}</th>
              <th>{{ __('admin.actions') }}</th>
            </tr>
          </thead>
          <tbody>
            @foreach($otps as $o)
              <tr>
                <td>{{ $o->id }}</td>
                <td>
                  @if(!empty($o->vendor))
                    {{ $o->vendor->full_name ?? $o->vendor->name }} <div class="small p">#{{ $o->vendor->id }} (Vendor)</div>
                  @elseif(!empty($o->user))
                    {{ $o->user->full_name }} <div class="small p">#{{ $o->user->id }}</div>
                  @else
                    <div class="small p">-</div>
                  @endif
                </td>
                <td>{{ ($o->country_code ? $o->country_code : '') . $o->phone }}</td>
                <td>{{ $o->purpose ?? '-' }}</td>
                <td>{{ $o->channel ?? 'sms' }}</td>
                <td><span class="mono">{{ __('admin.otps.masked') }}</span></td>
                <td>
                  @if($o->status==='active')<span class="badge-active small">{{ __('admin.otps.active') }}</span>
                  @elseif($o->status==='used')<span class="badge-neutral small">{{ __('admin.otps.used') }}</span>
                  @elseif($o->status==='expired')<span class="badge-warning small">{{ __('admin.otps.expired') }}</span>
                  @else<span class="badge-inactive small">{{ __('admin.otps.revoked') }}</span>@endif
                </td>
                <td>{{ \Carbon\Carbon::parse($o->expires_at)->diffForHumans() }}</td>
                <td>{{ \Carbon\Carbon::parse($o->created_at)->format('Y-m-d H:i') }}</td>
                <td>
                  <div class="actions">
                    <a href="{{ route('admin.otps.show', $o->id) }}" class="btn btn-ghost btn-sm">{{ __('admin.otps.view') }}</a>
                    @if($o->status !== 'revoked')
                      <form action="{{ route('admin.otps.revoke', $o->id) }}" method="POST" style="display:inline">
                        @csrf
                        @method('PATCH')
                        <button type="button" class="btn btn-success btn-sm js-confirm" data-confirm="{{ __('admin.otps.confirm_revoke') }}">{{ __('admin.otps.revoke') }}</button>
                      </form>
                    @endif
                    <form action="{{ route('admin.otps.destroy', $o->id) }}" method="POST" style="display:inline">
                      @csrf
                      @method('DELETE')
                      <button type="button" class="btn btn-danger btn-sm js-confirm" data-confirm="{{ __('admin.otps.confirm_delete') }}">{{ __('admin.otps.delete') }}</button>
                    </form>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>

        <div class="pagination-wrap">{{ $otps->appends(request()->query())->links() }}</div>
      @else
        <div style="padding:2.5rem;text-align:center">
          <div class="h2">{{ __('admin.otps.empty') }}</div>
          <p class="p">{{ __('admin.otps.empty') }}</p>
        </div>
      @endif
    </div>
  </div>
@endsection
