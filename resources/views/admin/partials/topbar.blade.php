<div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;">
  <div style="display:flex;align-items:center;gap:.75rem;">
    <button id="admin-burger" class="btn btn-ghost" type="button" aria-label="Open menu">☰</button>
    <div>
      <div class="h2" style="margin:0;">PS</div>
      <div class="small" style="margin-top:.1rem;">{{ __('admin.dashboard.topbar') ?? 'Admin' }}</div>
    </div>
  </div>

  <div style="display:flex;align-items:center;gap:.75rem;">
    <a class="btn btn-ghost" href="{{ route('admin.lang', app()->getLocale() == 'ar' ? 'en' : 'ar') }}">
      {{ app()->getLocale() == 'ar' ? 'EN' : 'ع' }}
    </a>

    {{-- Theme toggle --}}
    <a class="btn btn-ghost btn-sm" href="{{ route('admin.theme.switch', ($adminTheme ?? 'dark') === 'dark' ? 'light' : 'dark') }}"> 
      @if(($adminTheme ?? 'dark') === 'dark')
        ☀️
      @else
        🌙
      @endif
    </a>

    <span class="small" style="color:rgba(255,255,255,.75);">
      {{ auth('admin')->user()->name ?? '' }}
    </span>

    <form method="POST" action="{{ route('admin.logout') }}">
      @csrf
      <button class="btn btn-gold" type="submit">{{ __('admin.logout') }}</button>
    </form>
  </div>
</div>
