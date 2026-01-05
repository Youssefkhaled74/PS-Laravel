@extends('admin.layouts.app')

@section('content')
  <div class="card">
    <h2 class="h2">{{ __('admin.users.title') ?? __('admin.sidebar.users') }}</h2>
    <p class="p">{{ __('admin.users.subtitle') ?? 'صفحة المستخدمين (قيد التطوير)' }}</p>
  </div>
@endsection
