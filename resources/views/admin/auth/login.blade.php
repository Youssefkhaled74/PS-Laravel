@extends('admin.layouts.app')

@section('content')
  <div class="auth-card card">
    <div class="auth-header">
      <h1 class="h1">{{ __('admin.login.title') }}</h1>
      <p class="p">{{ __('admin.login.subtitle') }}</p>
    </div>

    <form class="form" method="POST" action="{{ route('admin.login.post') }}" novalidate>
      @csrf

      <div class="form-group">
        <label class="label" for="email">{{ __('admin.login.email') }}</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}" class="input" placeholder="admin@ps.test" required>
      </div>

      <div class="form-group">
        <label class="label" for="admin-password">{{ __('admin.login.password') }}</label>
        <div class="input-wrap">
          <input id="admin-password" name="password" type="password" class="input" placeholder="********" required>
          <button
            type="button"
            class="password-toggle"
            data-toggle="password"
            data-target="#admin-password"
            aria-label="Show password"
            aria-pressed="false">👁️</button>
        </div>
      </div>

      <div class="form-group auth-actions">
        <label style="display:flex;align-items:center;gap:.5rem;">
          <input type="checkbox" name="remember">
          <span class="small">{{ __('admin.login.remember') }}</span>
        </label>

        <a href="#" class="small">{{ __('admin.login.forgot') }}</a>
      </div>

      <button class="btn btn-gold" type="submit" style="width:100%;">
        {{ __('admin.login.submit') }}
      </button>

      <div class="auth-footer">
        <div>
          <a class="btn btn-ghost" href="{{ route('admin.lang', app()->getLocale() == 'ar' ? 'en' : 'ar') }}">
            {{ app()->getLocale() == 'ar' ? 'EN' : 'ع' }}
          </a>
        </div>

        <div style="display:flex;gap:.5rem;">
          <a class="btn btn-ghost btn-sm" href="{{ route('admin.theme.switch', ($adminTheme ?? 'dark') === 'dark' ? 'light' : 'dark') }}">
            @if(($adminTheme ?? 'dark') === 'dark')
              ☀️
            @else
              🌙
            @endif
          </a>
        </div>
      </div>
    </form>
  </div>
@endsection
