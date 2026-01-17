@extends('admin.layouts.app')

@section('content')
  <div class="page">
    <div class="page-header">
      <h1 class="h1">{{ $item->name }}</h1>
      <div>
        <a href="{{ route('admin.items.index') }}" class="btn btn-ghost">{{ __('admin.back') }}</a>
      </div>
    </div>

    <div class="grid-2">
      <div>
        <x-admin.info-card title="{{ __('admin.items.title') }}">
          <div class="kv-wrap">
            <div class="kv">
              <div class="kv__label">{{ __('admin.items.name') }}</div>
              <div class="kv__value">{{ $item->name }}</div>
            </div>
            <div class="kv">
              <div class="kv__label">{{ __('admin.sidebar.vendors') }}</div>
              <div class="kv__value">{{ $item->vendor->name ?? '-' }}</div>
            </div>
            <div class="kv">
              <div class="kv__label">{{ __('admin.price') ?? 'Price' }}</div>
              <div class="kv__value">{{ number_format(($item->price ?? 0)/100,2) }}</div>
            </div>
            <div class="kv">
              <div class="kv__label">{{ __('admin.status') }}</div>
              <div class="kv__value">{{ __('admin.items.status.' . $item->status) }}</div>
            </div>
            <div class="kv">
              <div class="kv__label">{{ __('admin.items.rejection_reason') }}</div>
              <div class="kv__value">{{ $item->rejection_reason ?? '-' }}</div>
            </div>
          </div>
          <div class="form-actions">
            @if($item->status !== 'approved')
              <form action="{{ route('admin.items.approve', $item) }}" method="POST">
                @csrf @method('PATCH')
                <button class="btn btn-gold">{{ __('admin.items.actions.approve') }}</button>
              </form>
            @endif

            @if($item->status !== 'rejected')
              <button class="btn btn-ghost" onclick="document.getElementById('reject-modal').classList.add('is-open')">{{ __('admin.items.actions.reject') }}</button>
            @endif
          </div>
        </x-admin.info-card>
      </div>

      <div>
        <x-admin.info-card title="Gallery">
          <div class="docs-grid">
            @foreach($item->images as $img)
              <div class="file-tile">
                <div class="file-thumb"><img src="{{ asset($img->path) }}" /></div>
              </div>
            @endforeach
          </div>
        </x-admin.info-card>
      </div>
    </div>

    <div id="reject-modal" class="collapse">
      <form action="{{ route('admin.items.reject', $item) }}" method="POST">
        @csrf @method('PATCH')
        <div class="field">
          <label class="label">{{ __('admin.items.reject_reason_label') }}</label>
          <textarea name="rejection_reason" class="input" rows="4"></textarea>
        </div>
        <div class="form-actions">
          <button type="button" class="btn btn-ghost" onclick="document.getElementById('reject-modal').classList.remove('is-open')">{{ __('admin.cancel') }}</button>
          <button class="btn btn-gold">{{ __('admin.items.actions.reject') }}</button>
        </div>
      </form>
    </div>
  </div>
@endsection
