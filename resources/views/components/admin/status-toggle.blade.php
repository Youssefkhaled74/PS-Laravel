@props([
  'action',
  'status' => 'inactive',
  'method' => 'PATCH',
  'confirm' => true,
  'confirmTitle' => __('admin.confirm'),
  'confirmText' => __('admin.status_toggle_confirm'),
  'activeLabel' => __('admin.active'),
  'inactiveLabel' => __('admin.inactive'),
  'size' => 'md',
  'variant' => 'pill',
])

@php
  $uid = 'status_toggle_'.bin2hex(random_bytes(4));
  $isActive = strtolower($status) === 'active';
  $sizeClass = $size === 'sm' ? 'btn-pill-sm' : 'btn-pill-md';
  $variantClass = $variant === 'icon' ? 'btn-pill-icon' : 'btn-pill';
  $label = $isActive ? $activeLabel : $inactiveLabel;
  $colorClass = $isActive ? 'btn-status-active' : 'btn-status-inactive';
@endphp

<form id="form_{{ $uid }}" action="{{ $action }}" method="POST" style="display:inline-block">
  @csrf
  @if(strtoupper($method) !== 'POST')
    @method($method)
  @endif

  <button
    type="button"
    class="btn {{ $variantClass }} {{ $sizeClass }} {{ $colorClass }} js-status-toggle"
    data-toggle-target="#form_{{ $uid }}"
    data-confirm-enabled="{{ $confirm ? '1' : '0' }}"
    data-confirm-title="{{ $confirmTitle }}"
    data-confirm-text="{{ $confirmText }}"
    aria-label="{{ $label }}"
    title="{{ $label }}"
  >
    <span class="btn-icon" aria-hidden="true">{!! $isActive ? '&#x2713;' : '&#x2013;' !!}</span>
    <span class="btn-text">{{ $label }}</span>
  </button>
</form>
