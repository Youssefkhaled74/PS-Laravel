<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PS - Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    @if(app()->getLocale() == 'ar')
      <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;700&display=swap" rel="stylesheet">
    @else
      <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    @endif
    @php $viteManifest = public_path('build/manifest.json'); @endphp
    @if (file_exists($viteManifest))
      @vite(['resources/css/app.css','resources/css/admin.css','resources/js/app.js','resources/js/admin.js'])
    @else
      <!-- Vite manifest not found — using simple public fallback assets. Copy resources/css/admin.css -> public/css/admin.css and resources/js/admin.js -> public/js/admin.js if missing -->
      <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
      <script src="{{ asset('js/admin.js') }}" defer></script>
    @endif
    <style>
      body{min-height:100vh;font-family:{{ app()->getLocale()=='ar' ? 'Tajawal, sans-serif' : 'Inter, sans-serif' }};}
      body.rtl{direction:rtl}
      body.ltr{direction:ltr}
    </style>
</head>
<body class="bg-black text-white">
  <div class="min-h-screen flex {{ app()->getLocale()=='ar' ? 'rtl' : 'ltr' }}">
    <aside id="admin-sidebar" class="w-64 hidden md:block bg-[#070707] p-4">
      @include('admin.partials.sidebar')
    </aside>
    <div class="flex-1 p-6">
      @include('admin.partials.topbar')
      <main class="mt-6">
        @include('admin.partials.flash')
        @yield('content')
      </main>
    </div>
  </div>
</body>
</html>
