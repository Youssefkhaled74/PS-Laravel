@extends('admin.layouts.app')

@section('content')
  <div class="card max-w-md mx-auto">
    <div class="title-wrap">
      <h1 class="h1">{{ __('admin.brands.create_title') }}</h1>
      <div class="small p">{{ __('admin.brands.form_hint') ?? '' }}</div>
    </div>

    <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data" class="form mt-4">
      @csrf
      <div class="grid grid-2 gap-6">
        <div class="form-group">
          <label class="label">{{ __('admin.brands.name_en') }}</label>
          <input type="text" name="name_en" value="{{ old('name_en') }}" class="input" required>
        </div>

        <div class="form-group">
          <label class="label">{{ __('admin.brands.name_ar') }}</label>
          <input type="text" name="name_ar" value="{{ old('name_ar') }}" class="input" required>
        </div>
      </div>

      <div class="grid grid-2 gap-6 mt-3">
        <div class="form-group">
          <label class="label">{{ __('admin.brands.status') }}</label>
          <select name="status" class="input">
            <option value="active">{{ __('admin.brands.active') }}</option>
            <option value="inactive">{{ __('admin.brands.inactive') }}</option>
          </select>
        </div>

        <div class="form-group">
          <label class="label">{{ __('admin.brands.sort_order') }}</label>
          <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="input">
        </div>
      </div>

      <div class="form-group mt-3">
        <label class="label">{{ __('admin.brands.logo') }}</label>
        <input type="file" name="logo" accept="image/*" class="input">
      </div>

      <div class="actions mt-4">
        <button class="btn btn-gold">{{ __('admin.save') }}</button>
        <a href="{{ route('admin.brands.index') }}" class="btn btn-ghost">{{ __('admin.cancel') }}</a>
      </div>
    </form>
  </div>
@endsection
