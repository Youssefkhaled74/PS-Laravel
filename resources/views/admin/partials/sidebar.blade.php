@php
  $is = fn(string $name) => request()->routeIs($name);
@endphp

<div class="brand">
  <div class="brand-left">
    <div class="logo"></div>
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

  <a href="{{ route('admin.users') }}" class="{{ $is('admin.users') ? 'active' : '' }}">
    {{ __('admin.sidebar.users') }}
  </a>

  <a href="#" class="">
    {{ __('admin.sidebar.vendors') }}
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

  <a href="#" class="">
    {{ __('admin.sidebar.settings') }}
  </a>
</nav>
