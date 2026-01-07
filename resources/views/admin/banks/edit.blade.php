@extends('admin.layouts.app')

@section('content')
  <div class="card max-w-md mx-auto">
    <div class="title-wrap">
      <h1 class="h1">{{ __('admin.banks.edit_title') }}</h1>
      <div class="small p">{{ __('admin.banks.form_hint') ?? '' }}</div>
    </div>

    <form action="{{ route('admin.banks.update', $bank->id) }}" method="POST" enctype="multipart/form-data" class="form mt-4">
      @csrf
      @method('PUT')
      <div class="grid grid-2 gap-6">
        <div class="form-group">
          <label class="label">{{ __('admin.banks.name_en') }}</label>
          <input type="text" name="name_en" value="{{ old('name_en', $bank->name_en) }}" class="input" required>
        </div>

        <div class="form-group">
          <label class="label">{{ __('admin.banks.name_ar') }}</label>
          <input type="text" name="name_ar" value="{{ old('name_ar', $bank->name_ar) }}" class="input" required>
        </div>
      </div>

      <div class="grid grid-2 gap-6 mt-3">
        <div class="form-group">
          <label class="label">{{ __('admin.banks.status') }}</label>
          <select name="status" class="input">
            <option value="active" {{ $bank->status === 'active' ? 'selected' : '' }}>{{ __('admin.banks.active') }}</option>
            <option value="inactive" {{ $bank->status === 'inactive' ? 'selected' : '' }}>{{ __('admin.banks.inactive') }}</option>
          </select>
        </div>

        <div class="form-group">
          <label class="label">{{ __('admin.banks.sort_order') }}</label>
          <input type="number" name="sort_order" value="{{ old('sort_order', $bank->sort_order) }}" class="input">
        </div>
      </div>

      <div class="form-group mt-3">
        <x-admin.upload-preview
          name="logo"
          label="{{ __('admin.banks.logo') }}"
          currentUrl="{{ $bank->logo ? asset($bank->logo) : '' }}"
          accept="image/png,image/jpeg,image/webp"
          hint="{{ __('admin.banks.replace_hint') }}"
          variant="square"
          size="md"
        />
      </div>

      <div class="actions mt-4">
        <button class="btn btn-gold">{{ __('admin.update') }}</button>
        <a href="{{ route('admin.banks.index') }}" class="btn btn-ghost">{{ __('admin.cancel') }}</a>
      </div>
    </form>
  </div>
@endsection
