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

  @php $viteManifest = public_path('build/manifest.json'); @endphp
  @if (file_exists($viteManifest))
    @vite(['resources/css/app.css','resources/css/admin.css','resources/js/app.js','resources/js/admin.js'])
  @else
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <script src="{{ asset('js/admin.js') }}" defer></script>
  @endif

  <style>
    body{
      font-family: {{ app()->getLocale()=='ar' ? 'Tajawal, sans-serif' : 'Inter, sans-serif' }};
    }
  </style>
</head>

<body>
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
</body>
</html>
