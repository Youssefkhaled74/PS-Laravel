<div class="text-center mb-6">
  <h1 class="text-2xl font-bold">{{ __('admin.brand') }}</h1>
  <p class="text-sm text-[#9CA3AF]">{{ __('admin.sidebar.title') }}</p>
</div>
<nav class="space-y-2">
  <a href="{{ route('admin.dashboard') }}" class="block py-2 px-3 rounded hover:bg-[#111]">{{ __('admin.sidebar.dashboard') }}</a>
  <a href="{{ route('admin.users') }}" class="block py-2 px-3 rounded hover:bg-[#111]">{{ __('admin.sidebar.users') }}</a>
  <a href="#" class="block py-2 px-3 rounded hover:bg-[#111]">{{ __('admin.sidebar.vendors') }}</a>
  <a href="#" class="block py-2 px-3 rounded hover:bg-[#111]">{{ __('admin.sidebar.orders') }}</a>
  <a href="#" class="block py-2 px-3 rounded hover:bg-[#111]">{{ __('admin.sidebar.products') }}</a>
  <a href="#" class="block py-2 px-3 rounded hover:bg-[#111]">{{ __('admin.sidebar.settings') }}</a>
</nav>
