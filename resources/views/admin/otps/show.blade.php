@extends('admin.layouts.app')

@section('content')
  <div class="card max-w-md mx-auto">
    <div class="toolbar">
      <div class="title-wrap">
        <h1 class="h1">{{ __('admin.otps.title') }}</h1>
        <div class="small p">{{ __('admin.otps.subtitle') }}</div>
      </div>
      <div class="actions">
        <a href="{{ route('admin.otps.index') }}" class="btn btn-ghost">{{ __('admin.back') ?? 'Back' }}</a>
      </div>
    </div>

    <div class="mt-6 card">
      <h3 class="h3">{{ __('admin.otps.details') ?? 'OTP Details' }}</h3>
      <div class="divider"></div>
      <div class="p"><strong>{{ __('admin.otps.contact') }}:</strong> {{ ($otp->country_code ?? '') . $otp->phone }}</div>
      <div class="p"><strong>{{ __('admin.otps.purpose') }}:</strong> {{ $otp->purpose ?? '-' }}</div>
      @if(!empty($otp->plain_otp))
        <div class="p"><strong>{{ __('admin.otps.plain_otp') ?? 'OTP' }}:</strong> <span class="mono">{{ $otp->plain_otp }}</span></div>
      @endif
      @if(!empty($otp->vendor))
        <div class="p"><strong>{{ __('admin.otps.vendor') ?? 'Vendor' }}:</strong> {{ $otp->vendor->full_name ?? $otp->vendor->name }} <div class="small p">#{{ $otp->vendor->id }}</div></div>
      @elseif(!empty($otp->user))
        <div class="p"><strong>{{ __('admin.otps.user') ?? 'User' }}:</strong> {{ $otp->user->full_name }} <div class="small p">#{{ $otp->user->id }}</div></div>
      @endif

      <div class="p"><strong>{{ __('admin.otps.used_at') ?? 'Used At' }}:</strong>
        {{ isset($otp->verified_at) ? \Carbon\Carbon::parse($otp->verified_at)->format('Y-m-d H:i') : (isset($otp->consumed_at) ? \Carbon\Carbon::parse($otp->consumed_at)->format('Y-m-d H:i') : '-') }}</div>
      <div class="p"><strong>{{ __('admin.otps.revoked_at') ?? 'Revoked At' }}:</strong>
        {{ isset($otp->revoked_at) ? \Carbon\Carbon::parse($otp->revoked_at)->format('Y-m-d H:i') : '-' }}</div>
      <div class="p"><strong>{{ __('admin.otps.masked') }}:</strong> <span class="mono">{{ __('admin.otps.masked') }}</span></div>
      <div class="p"><strong>{{ __('admin.otps.status') }}:</strong> {{ ucfirst($otp->status) }}</div>
      <div class="p"><strong>{{ __('admin.otps.expires_at') }}:</strong> {{ \Carbon\Carbon::parse($otp->expires_at)->format('Y-m-d H:i') }}</div>
      <div class="p"><strong>{{ __('admin.otps.created_at') }}:</strong> {{ \Carbon\Carbon::parse($otp->created_at)->format('Y-m-d H:i') }}</div>
      <div class="p"><strong>{{ __('admin.otps.attempts') }}:</strong> {{ $otp->attempts_count ?? 0 }}</div>
      <div class="divider"></div>

      <div class="actions">
        @if($otp->status !== 'revoked')
          <form action="{{ route('admin.otps.revoke', $otp->id) }}" method="POST" style="display:inline">
            @csrf
            @method('PATCH')
            <button type="button" class="btn btn-success js-confirm" data-confirm="{{ __('admin.otps.confirm_revoke') }}">{{ __('admin.otps.revoke') }}</button>
          </form>
        @endif

        <form action="{{ route('admin.otps.destroy', $otp->id) }}" method="POST" style="display:inline">
          @csrf
          @method('DELETE')
          <button type="button" class="btn btn-danger js-confirm" data-confirm="{{ __('admin.otps.confirm_delete') }}">{{ __('admin.otps.delete') }}</button>
        </form>

        @if(false)
          <form action="{{ route('admin.otps.resend', $otp->id) }}" method="POST" style="display:inline">
            @csrf
            <button class="btn btn-gold">{{ __('admin.otps.resend') }}</button>
          </form>
        @endif
      </div>
    </div>
  </div>
@endsection
