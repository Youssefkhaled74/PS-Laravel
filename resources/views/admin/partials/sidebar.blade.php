@php
  $is = fn(string $name) => request()->routeIs($name);
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

<nav class="nav">
  <a href="{{ route('admin.dashboard') }}" class="{{ $is('admin.dashboard') ? 'active' : '' }}">
    {{ __('admin.sidebar.dashboard') }}
  </a>

  <a href="{{ route('admin.users.index') }}" class="{{ $is('admin.users.index') ? 'active' : '' }}">
    {{ __('admin.sidebar.users') }}
  </a>

  <a href="{{ route('admin.brands.index') }}" class="{{ str_contains(request()->route()->getName() ?? '', 'admin.brands') ? 'active' : '' }}">
    {{ __('admin.sidebar.brands') }}
  </a>

  <a href="{{ route('admin.banks.index') }}" class="{{ str_contains(request()->route()->getName() ?? '', 'admin.banks') ? 'active' : '' }}">
    {{ __('admin.sidebar.banks') }}
  </a>

  <a href="{{ route('admin.vendor-packages.index') }}" class="{{ str_contains(request()->route()->getName() ?? '', 'admin.vendor-packages') ? 'active' : '' }}">
    {{ __('admin.sidebar.vendor_packages') }}
  </a>

  <a href="{{ route('admin.otps.index') }}" class="{{ str_contains(request()->route()->getName() ?? '', 'admin.otps') ? 'active' : '' }}">
    {{ __('admin.sidebar.otps') }}
  </a>

  <a href="{{ route('admin.vendors.index') }}" class="{{ str_contains(request()->route()->getName() ?? '', 'admin.vendors') ? 'active' : '' }}">
    {{ __('admin.sidebar.vendors') }}
  </a>

  <a href="{{ route('admin.stories.index') }}" class="{{ str_contains(request()->route()->getName() ?? '', 'admin.stories') ? 'active' : '' }}">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:inline-block;vertical-align:middle;margin-right:.35rem">
      <circle cx="12" cy="12" r="10"></circle>
      <polygon points="10 8 16 12 10 16 10 8"></polygon>
    </svg>
    {{ __('admin.sidebar.stories') }}
  </a>

  <a href="#" class="">
    {{ __('admin.sidebar.orders') }}
  </a>

  <a href="#" class="">
    {{ __('admin.sidebar.products') }}
  </a>
  <a href="{{ route('admin.categories.index') }}" class="{{ $is('admin.categories.index') ? 'active' : '' }}">
    {{ __('admin.sidebar.categories') }}
  </a>

  <a href="{{ route('admin.admins.index') }}" class="{{ str_contains(request()->route()->getName() ?? '', 'admin.admins') ? 'active' : '' }}">
    {{ __('admin.sidebar.admins') }}
  </a>

  <a href="{{ route('admin.legal-pages.index') }}" class="{{ str_contains(request()->route()->getName() ?? '', 'admin.legal-pages') ? 'active' : '' }}">
    {{ __('admin.sidebar.legal_pages') }}
  </a>

  <a href="#" class="">
    {{ __('admin.sidebar.settings') }}
  </a>
</nav>
