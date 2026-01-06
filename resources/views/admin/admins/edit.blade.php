@extends('admin.layouts.app')

@section('content')
  <div class="card">
    <div class="toolbar">
      <div class="title-wrap">
        <h2 class="h2">{{ __('admin.admins.edit_title') }}</h2>
        <div class="small p">{{ __('admin.admins.form_hint') ?? '' }}</div>
      </div>
    </div>

    <div class="divider"></div>

    <form action="{{ route('admin.admins.update', $admin->id) }}" method="POST" class="form">
      @csrf
      @method('PUT')

      <div class="form-group">
        <label class="label">{{ __('admin.admins.name') }}</label>
        <input type="text" name="name" class="input" value="{{ old('name', $admin->name) }}" required>
      </div>

      <div class="form-group">
        <label class="label">{{ __('admin.admins.email') }}</label>
        <input type="email" name="email" class="input" value="{{ old('email', $admin->email) }}" required>
      </div>

      <div class="form-group">
        <label class="label">{{ __('admin.admins.password') }}</label>
        <input type="password" name="password" class="input" placeholder="{{ __('admin.admins.password') }}">
      </div>

      <div class="form-group">
        <label class="label">{{ __('admin.admins.password_confirmation') }}</label>
        <input type="password" name="password_confirmation" class="input" placeholder="{{ __('admin.admins.password_confirmation') }}">
      </div>

      <div class="form-group">
        <label class="label">{{ __('admin.admins.status') }}</label>
        <select name="status" class="input">
          <option value="active" {{ $admin->status=='active' ? 'selected' : '' }}>{{ __('admin.admins.active') }}</option>
          <option value="inactive" {{ $admin->status=='inactive' ? 'selected' : '' }}>{{ __('admin.admins.inactive') }}</option>
        </select>
      </div>

      <div class="toolbar" style="margin-top:1rem">
        <div class="small p">&nbsp;</div>
        <div class="actions">
          <button class="btn btn-gold">{{ __('admin.update') }}</button>
          <a href="{{ route('admin.admins.index') }}" class="btn btn-ghost">{{ __('admin.cancel') }}</a>
        </div>
      </div>
    </form>
  </div>
@endsection
