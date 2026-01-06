@extends('admin.layouts.app')

@section('content')
  <div class="card max-w-md mx-auto">
    <div class="title-wrap">
      <h1 class="h1">{{ __('admin.brands.edit_title') }}</h1>
      <div class="small p">{{ __('admin.brands.form_hint') ?? '' }}</div>
    </div>

    <form action="{{ route('admin.brands.update', $brand->id) }}" method="POST" enctype="multipart/form-data" class="form mt-4">
      @csrf
      @method('PUT')
      <div class="grid grid-2 gap-6">
        <div class="form-group">
          <label class="label">{{ __('admin.brands.name_en') }}</label>
          <input type="text" name="name_en" value="{{ old('name_en', $brand->name_en) }}" class="input" required>
        </div>

        <div class="form-group">
          <label class="label">{{ __('admin.brands.name_ar') }}</label>
          <input type="text" name="name_ar" value="{{ old('name_ar', $brand->name_ar) }}" class="input" required>
        </div>
      </div>

      <div class="grid grid-2 gap-6 mt-3">
        <div class="form-group">
          <label class="label">{{ __('admin.brands.status') }}</label>
          <select name="status" class="input">
            <option value="active" {{ $brand->status === 'active' ? 'selected' : '' }}>{{ __('admin.brands.active') }}</option>
            <option value="inactive" {{ $brand->status === 'inactive' ? 'selected' : '' }}>{{ __('admin.brands.inactive') }}</option>
          </select>
        </div>

        <div class="form-group">
          <label class="label">{{ __('admin.brands.sort_order') }}</label>
          <input type="number" name="sort_order" value="{{ old('sort_order', $brand->sort_order) }}" class="input">
        </div>
      </div>

      <div class="form-group mt-3">
        <label class="label">{{ __('admin.brands.logo') }}</label>
        <div class="card-soft">
          <div style="display:flex;gap:.75rem;align-items:center">
            <img src="{{ asset($brand->logo ?? 'images/brand-placeholder.png') }}" class="logo-thumb" alt="logo">
            <div>
              <div class="small p">{{ __('admin.brands.current_logo') ?? '' }}</div>
              <div class="small p">{{ __('admin.brands.replace_hint') ?? '' }}</div>
            </div>
          </div>
        </div>
        <input type="file" name="logo" accept="image/*" class="input mt-2">
      </div>

      <div class="actions mt-4">
        <button class="btn btn-gold">{{ __('admin.update') }}</button>
        <a href="{{ route('admin.brands.index') }}" class="btn btn-ghost">{{ __('admin.cancel') }}</a>
      </div>
    </form>
  </div>
@endsection
