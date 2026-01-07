<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PS - Admin</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  @if(app()->getLocale() == 'ar')
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;700;800&display=swap" rel="stylesheet">
  @else
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
  @endif

  {{-- ✅ No Vite, No npm --}}
  <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
  <script src="{{ asset('js/admin.js') }}" defer></script>

  <style>
    body{
      font-family: {{ app()->getLocale()=='ar' ? 'Tajawal, sans-serif' : 'Inter, sans-serif' }};
    }
  </style>
</head>

<body class="theme-{{ $adminTheme ?? 'dark' }}">
  @if(auth('admin')->check())
    <div class="admin-shell">
      <aside id="admin-sidebar" class="admin-sidebar">
        @include('admin.partials.sidebar')
      </aside>

      <div class="admin-content">
        <header class="admin-topbar">
          @include('admin.partials.topbar')
        </header>

        <main class="container">
          @include('admin.partials.flash')
          @yield('content')
        </main>
      </div>
    </div>

    <div id="admin-overlay"></div>
  @else
    <div class="auth-wrap">
      <div style="width:100%; max-width:520px;">
        @include('admin.partials.flash')
        @yield('content')
      </div>
    </div>
  @endif

  {{-- Global admin modal (used by actions like status toggle, delete, etc.) --}}
  <div id="admin-global-modal" class="admin-modal" aria-hidden="true">
    <div class="admin-modal-backdrop" data-modal-close></div>
    <div class="admin-modal-card" role="dialog" aria-modal="true" aria-labelledby="admin-modal-title">
      <header class="admin-modal-header">
        <h3 id="admin-modal-title" class="h3">Modal Title</h3>
      </header>
      <div class="admin-modal-body">
        <p id="admin-modal-text">Modal description</p>
      </div>
      <footer class="admin-modal-footer">
        <button type="button" class="btn btn-ghost" data-modal-cancel>{{ __('admin.cancel') }}</button>
        <button type="button" class="btn btn-danger" data-modal-confirm>{{ __('admin.confirm') }}</button>
      </footer>
    </div>
  </div>
</body>
</html>
