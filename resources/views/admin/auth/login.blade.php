@extends('admin.layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-20">
  <div class="admin-card">
    <h1 class="text-2xl font-bold mb-2">{{ __('admin.login.title') }}</h1>
    <p class="text-sm text-[#9CA3AF] mb-4">{{ __('admin.login.subtitle') }}</p>

    <form method="POST" action="{{ route('admin.login.post') }}">
      @csrf
      <div class="mb-3">
        <label class="block text-sm mb-1">{{ __('admin.login.email') }}</label>
        <input name="email" type="email" value="{{ old('email') }}" class="w-full input-dark" />
      </div>

      <div class="mb-3 relative">
        <label class="block text-sm mb-1">{{ __('admin.login.password') }}</label>
        <input id="admin-password" name="password" type="password" class="w-full input-dark pr-10" />
        <button type="button" class="absolute top-8 {{ app()->getLocale()=='ar' ? 'left-3' : 'right-3' }} toggle-password" data-target="#admin-password">👁️</button>
      </div>

      <div class="flex items-center justify-between mb-4">
        <div>
          <label class="inline-flex items-center"><input type="checkbox" name="remember"> <span class="mr-2">{{ __('admin.login.remember') }}</span></label>
        </div>
        <div><a href="#" class="text-sm text-[#9CA3AF]">{{ __('admin.login.forgot') }}</a></div>
      </div>

      <div class="mb-3">
        <button class="w-full btn-gold">{{ __('admin.login.submit') }}</button>
      </div>
    </form>
  </div>
</div>
@endsection
