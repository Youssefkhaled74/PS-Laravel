@extends('admin.layouts.app')

@section('content')
  <div class="card">
    <div class="toolbar">
      <div class="title-wrap">
        <h2 class="h2">{{ __('admin.categories.edit_title') }}</h2>
        <div class="small p">{{ __('admin.categories.form_hint') }}</div>
      </div>
    </div>

    <div class="divider"></div>

    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" class="form">
      @csrf
      @method('PUT')

      <div class="form-group">
        <label class="label">{{ __('admin.categories.name_en') }}</label>
        <input type="text" name="name_en" class="input" value="{{ old('name_en', $category->name_en) }}">
      </div>

      <div class="form-group">
        <label class="label">{{ __('admin.categories.name_ar') }}</label>
        <input type="text" name="name_ar" class="input" value="{{ old('name_ar', $category->name_ar) }}">
      </div>

      <div class="form-group">
        <label class="label">{{ __('admin.categories.status') }}</label>
        <select name="status" class="input">
          <option value="active" {{ $category->status=='active' ? 'selected' : '' }}>{{ __('admin.categories.active') }}</option>
          <option value="inactive" {{ $category->status=='inactive' ? 'selected' : '' }}>{{ __('admin.categories.inactive') }}</option>
        </select>
      </div>

      <div class="form-group">
        <label class="label">{{ __('admin.categories.sort_order') }}</label>
        <input type="number" name="sort_order" class="input" value="{{ old('sort_order', $category->sort_order) }}">
      </div>

      <div class="toolbar" style="margin-top:1rem">
        <div class="small p">&nbsp;</div>
        <div class="actions">
          <button class="btn btn-gold">{{ __('admin.update') }}</button>
          <a href="{{ route('admin.categories.index') }}" class="btn btn-ghost">{{ __('admin.cancel') }}</a>
        </div>
      </div>
    </form>
  </div>
@endsection
