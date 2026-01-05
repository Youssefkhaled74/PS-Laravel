<div class="flex items-center justify-between">
  <div class="flex items-center gap-4">
    <button id="admin-burger" class="md:hidden px-2 py-1 bg-[#111] rounded">☰</button>
    <h2 class="text-xl font-semibold">{{ __('admin.brand') }}</h2>
  </div>
  <div class="flex items-center gap-4">
    <a class="text-sm text-[#9CA3AF] px-3 py-1 rounded hover:bg-[#111]" href="{{ route('admin.lang', app()->getLocale() == 'ar' ? 'en' : 'ar') }}">{{ app()->getLocale() == 'ar' ? 'EN' : 'ع' }}</a>
    <span class="text-sm text-[#9CA3AF]">{{ auth('admin')->user()->name ?? '' }}</span>
    <form method="POST" action="{{ route('admin.logout') }}">@csrf
      <button class="btn-gold">{{ __('admin.logout') }}</button>
    </form>
  </div>
</div>
