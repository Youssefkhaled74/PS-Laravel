@extends('admin.layouts.app')

@section('content')
  <div class="card max-w-md mx-auto">
    <div class="title-wrap">
      <h1 class="h1">{{ __('admin.vendor_packages.edit_title') }}</h1>
      <div class="small p">{{ __('admin.vendor_packages.form_hint') ?? '' }}</div>
    </div>

    <form action="{{ route('admin.vendor-packages.update', $package->id) }}" method="POST" class="form mt-4">
      @csrf
      @method('PUT')

      <div class="form-group">
        <label class="label">{{ __('admin.vendor_packages.key') }}</label>
        <input type="text" name="key" value="{{ old('key', $package->key) }}" class="input" required>
      </div>

      <div class="grid grid-2 gap-6 mt-3">
        <div class="form-group">
          <label class="label">{{ __('admin.vendor_packages.name_en') }}</label>
          <input type="text" name="name[en]" value="{{ old('name.en', $package->name['en'] ?? '') }}" class="input" required>
        </div>
        <div class="form-group">
          <label class="label">{{ __('admin.vendor_packages.name_ar') }}</label>
          <input type="text" name="name[ar]" value="{{ old('name.ar', $package->name['ar'] ?? '') }}" class="input" required>
        </div>
      </div>

      <div class="grid grid-2 gap-6 mt-3">
        <div class="form-group">
          <label class="label">{{ __('admin.vendor_packages.monthly_price') }}</label>
          <input type="number" name="monthly_price" value="{{ old('monthly_price', $package->monthly_price) }}" class="input" required>
        </div>
        <div class="form-group">
          <label class="label">{{ __('admin.vendor_packages.yearly_price') }}</label>
          <input type="number" name="yearly_price" value="{{ old('yearly_price', $package->yearly_price) }}" class="input" required>
        </div>
      </div>

      <div class="grid grid-2 gap-6 mt-3">
        <div class="form-group">
          <label class="label">{{ __('admin.vendor_packages.currency') }}</label>
          <input type="text" name="currency" value="{{ old('currency', $package->currency) }}" class="input">
        </div>
        <div class="form-group">
          <label class="label">{{ __('admin.vendor_packages.status') }}</label>
          <select name="status" class="input">
            <option value="active" {{ $package->status === 'active' ? 'selected' : '' }}>{{ __('admin.vendor_packages.active') }}</option>
            <option value="inactive" {{ $package->status === 'inactive' ? 'selected' : '' }}>{{ __('admin.vendor_packages.inactive') }}</option>
          </select>
        </div>
      </div>

      <div class="form-group mt-3">
        <label class="label">{{ __('admin.vendor_packages.sort_order') }}</label>
        <input type="number" name="sort_order" value="{{ old('sort_order', $package->sort_order) }}" class="input">
      </div>

      <div class="actions mt-4">
        <button class="btn btn-gold">{{ __('admin.update') }}</button>
        <a href="{{ route('admin.vendor-packages.index') }}" class="btn btn-ghost">{{ __('admin.cancel') }}</a>
      </div>
    </form>
  </div>
@endsection
