@extends('admin.layouts.app')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
  <div class="admin-card">
    <h3 class="text-sm text-[#9CA3AF]">{{ __('admin.sidebar.users') }}</h3>
    <div class="text-2xl font-bold">{{ $stats['users'] }}</div>
  </div>
  <div class="admin-card">
    <h3 class="text-sm text-[#9CA3AF]">{{ __('admin.sidebar.vendors') }}</h3>
    <div class="text-2xl font-bold">{{ $stats['vendors'] }}</div>
  </div>
  <div class="admin-card">
    <h3 class="text-sm text-[#9CA3AF]">{{ __('admin.sidebar.orders') }}</h3>
    <div class="text-2xl font-bold">{{ $stats['orders_today'] }}</div>
  </div>
</div>

<div class="mt-6 admin-card">
  <h3 class="text-lg font-semibold mb-4">{{ __('admin.dashboard.title') }}</h3>
  <table class="w-full text-sm">
    <thead class="text-left text-[#9CA3AF]"><tr><th>الحدث</th><th>المستخدم</th><th>الوقت</th></tr></thead>
    <tbody>
      <tr><td>تم تسجيل مستخدم جديد</td><td>محمد</td><td>قبل 2 ساعة</td></tr>
      <tr><td>تم إنشاء طلب</td><td>علي</td><td>قبل 3 ساعة</td></tr>
    </tbody>
  </table>
</div>

@endsection
