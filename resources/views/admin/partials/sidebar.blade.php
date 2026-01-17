@php
  $is = fn(string $name) => request()->routeIs($name);

  // Small helper to keep the sidebar consistent
  $icon = function (string $key) {
    return match ($key) {
      'dashboard' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 13h8V3H3v10zM13 21h8V11h-8v10zM13 3h8v6h-8V3zM3 21h8v-6H3v6z"/></svg>',
      'users'     => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
      'brands'    => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 12V7a2 2 0 0 0-2-2h-5l-2-2H6a2 2 0 0 0-2 2v5"/><path d="M4 12v7a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-7"/><path d="M8 15h8"/></svg>',
      'banks'     => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 10h18"/><path d="M5 10V20"/><path d="M9 10V20"/><path d="M15 10V20"/><path d="M19 10V20"/><path d="M4 20h16"/><path d="M12 3l9 7H3l9-7z"/></svg>',
      'packages'  => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4a2 2 0 0 0 1-1.73z"/><path d="M3.3 7l8.7 5 8.7-5"/><path d="M12 22V12"/></svg>',
      'otps'      => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="4" width="16" height="16" rx="3"/><path d="M8 12h8"/><path d="M8 8h4"/><path d="M8 16h6"/></svg>',
      'vendors'   => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18"/><path d="M5 21V7l7-4 7 4v14"/><path d="M9 21v-8h6v8"/><path d="M9 9h.01"/><path d="M15 9h.01"/></svg>',
      'stories'   => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polygon points="10 8 16 12 10 16 10 8"></polygon></svg>',
      'orders'    => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2h12l2 7H4l2-7z"/><path d="M4 9v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9"/><path d="M9 13h6"/></svg>',
      'products'  => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 8a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8z"/><path d="M7 6V4a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2"/><path d="M3 12h18"/></svg>',
      'categories'=> '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 5h8v6H3V5z"/><path d="M13 5h8v4h-8V5z"/><path d="M13 11h8v8h-8v-8z"/><path d="M3 13h8v6H3v-6z"/></svg>',
      'admins'    => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 22v-2a6 6 0 0 1 6-6h4a6 6 0 0 1 6 6v2"/><path d="M19 8l2 2"/><path d="M5 8l-2 2"/></svg>',
      'legal'     => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M8 13h8"/><path d="M8 17h6"/></svg>',
      'settings'  => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 15.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/><path d="M19.4 15a7.9 7.9 0 0 0 .1-2l2-1.5-2-3.4-2.4.6a8 8 0 0 0-1.7-1L15 2h-6l-.4 2.7a8 8 0 0 0-1.7 1l-2.4-.6-2 3.4L4.6 13a7.9 7.9 0 0 0 .1 2L2.7 16.5l2 3.4 2.4-.6a8 8 0 0 0 1.7 1L9 22h6l.4-2.7a8 8 0 0 0 1.7-1l2.4.6 2-3.4L19.4 15z"/></svg>',
      default     => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>',
    };
  };
@endphp

<div class="brand">
  <div class="brand-left">
    <div class="logo">PS</div>
    <div>
      <div class="h2">{{ __('admin.brand') }}</div>
      <div class="small">{{ __('admin.sidebar.title') }}</div>
    </div>
  </div>

  <button id="admin-close" class="admin-close" type="button">✕</button>
</div>

<nav class="nav nav--icons">
  <a href="{{ route('admin.dashboard') }}" class="{{ $is('admin.dashboard') ? 'active' : '' }}">
    <span class="nav-ic">{!! $icon('dashboard') !!}</span>
    <span class="nav-tx">{{ __('admin.sidebar.dashboard') }}</span>
  </a>

  <a href="{{ route('admin.users.index') }}" class="{{ $is('admin.users.index') ? 'active' : '' }}">
    <span class="nav-ic">{!! $icon('users') !!}</span>
    <span class="nav-tx">{{ __('admin.sidebar.users') }}</span>
  </a>

  <a href="{{ route('admin.brands.index') }}" class="{{ str_contains(request()->route()->getName() ?? '', 'admin.brands') ? 'active' : '' }}">
    <span class="nav-ic">{!! $icon('brands') !!}</span>
    <span class="nav-tx">{{ __('admin.sidebar.brands') }}</span>
  </a>

  <a href="{{ route('admin.banks.index') }}" class="{{ str_contains(request()->route()->getName() ?? '', 'admin.banks') ? 'active' : '' }}">
    <span class="nav-ic">{!! $icon('banks') !!}</span>
    <span class="nav-tx">{{ __('admin.sidebar.banks') }}</span>
  </a>

  <a href="{{ route('admin.vendor-packages.index') }}" class="{{ str_contains(request()->route()->getName() ?? '', 'admin.vendor-packages') ? 'active' : '' }}">
    <span class="nav-ic">{!! $icon('packages') !!}</span>
    <span class="nav-tx">{{ __('admin.sidebar.vendor_packages') }}</span>
  </a>

  <a href="{{ route('admin.otps.index') }}" class="{{ str_contains(request()->route()->getName() ?? '', 'admin.otps') ? 'active' : '' }}">
    <span class="nav-ic">{!! $icon('otps') !!}</span>
    <span class="nav-tx">{{ __('admin.sidebar.otps') }}</span>
  </a>

  <a href="{{ route('admin.vendors.index') }}" class="{{ str_contains(request()->route()->getName() ?? '', 'admin.vendors') ? 'active' : '' }}">
    <span class="nav-ic">{!! $icon('vendors') !!}</span>
    <span class="nav-tx">{{ __('admin.sidebar.vendors') }}</span>
  </a>

  <a href="{{ route('admin.stories.index') }}" class="{{ str_contains(request()->route()->getName() ?? '', 'admin.stories') ? 'active' : '' }}">
    <span class="nav-ic">{!! $icon('stories') !!}</span>
    <span class="nav-tx">{{ __('admin.sidebar.stories') }}</span>
  </a>

  <a href="#" class="">
    <span class="nav-ic">{!! $icon('orders') !!}</span>
    <span class="nav-tx">{{ __('admin.sidebar.orders') }}</span>
  </a>

  <a href="#" class="">
    <span class="nav-ic">{!! $icon('products') !!}</span>
    <span class="nav-tx">{{ __('admin.sidebar.products') }}</span>
  </a>

  <a href="{{ route('admin.items.index') }}" class="{{ str_contains(request()->route()->getName() ?? '', 'admin.items') ? 'active' : '' }}">
    <span class="nav-ic">{!! $icon('products') !!}</span>
    <span class="nav-tx">{{ __('admin.sidebar.items') }}</span>
  </a>

  <a href="{{ route('admin.categories.index') }}" class="{{ $is('admin.categories.index') ? 'active' : '' }}">
    <span class="nav-ic">{!! $icon('categories') !!}</span>
    <span class="nav-tx">{{ __('admin.sidebar.categories') }}</span>
  </a>

  <a href="{{ route('admin.admins.index') }}" class="{{ str_contains(request()->route()->getName() ?? '', 'admin.admins') ? 'active' : '' }}">
    <span class="nav-ic">{!! $icon('admins') !!}</span>
    <span class="nav-tx">{{ __('admin.sidebar.admins') }}</span>
  </a>

  <a href="{{ route('admin.legal-pages.index') }}" class="{{ str_contains(request()->route()->getName() ?? '', 'admin.legal-pages') ? 'active' : '' }}">
    <span class="nav-ic">{!! $icon('legal') !!}</span>
    <span class="nav-tx">{{ __('admin.sidebar.legal_pages') }}</span>
  </a>

  <a href="#" class="">
    <span class="nav-ic">{!! $icon('settings') !!}</span>
    <span class="nav-tx">{{ __('admin.sidebar.settings') }}</span>
  </a>
</nav>

{{-- Optional: put this style in the same blade (or your sidebar css file) --}}
<style>
  .nav--icons a{display:flex;align-items:center;gap:.65rem}
  .nav-ic{display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:10px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.08)}
  .nav a.active .nav-ic{background:rgba(255,255,255,.10);border-color:rgba(255,255,255,.14)}
  .nav-ic svg{width:16px;height:16px}
  .nav-tx{line-height:1}
</style>
