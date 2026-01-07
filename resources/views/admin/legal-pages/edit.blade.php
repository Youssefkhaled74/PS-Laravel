@php
  $lang = app()->getLocale();
  $isRtl = $lang === 'ar';
@endphp

<x-admin.container>
  <div class="split-editor">
    <div class="editor-column">
      <form method="post" action="{{ route('admin.legal-pages.update', $page->key) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
          <label>{{ __('admin.legal_pages.title_en') }}</label>
          <input type="text" name="title[en]" value="{{ old('title.en', $page->title['en'] ?? '') }}" class="input">
        </div>

        <div class="form-group">
          <label>{{ __('admin.legal_pages.title_ar') }}</label>
          <input type="text" name="title[ar]" value="{{ old('title.ar', $page->title['ar'] ?? '') }}" class="input text-right">
        </div>

        <div class="form-group">
          <label>{{ __('admin.legal_pages.content_en') }}</label>
          <textarea name="content[en]" class="textarea monospace" data-live-preview-target="en">{{ old('content.en', $page->content['en'] ?? '') }}</textarea>
        </div>

        <div class="form-group">
          <label>{{ __('admin.legal_pages.content_ar') }}</label>
          <textarea name="content[ar]" class="textarea monospace text-right" data-live-preview-target="ar">{{ old('content.ar', $page->content['ar'] ?? '') }}</textarea>
        </div>

        <div class="form-group">
          <label>{{ __('admin.legal_pages.status') }}</label>
          <select name="status" class="input">
            <option value="draft" {{ $page->status === 'draft' ? 'selected' : '' }}>{{ __('admin.legal_pages.draft') }}</option>
            <option value="published" {{ $page->status === 'published' ? 'selected' : '' }}>{{ __('admin.legal_pages.published') }}</option>
          </select>
        </div>

        <div class="form-group">
          <div class="muted">{{ __('admin.legal_pages.version') ?? 'Version' }}: {{ $page->version }}</div>
        </div>

        <div class="sticky-actions">
          <button class="btn primary" type="submit">{{ __('admin.save') }}</button>
          <a href="{{ route('admin.legal-pages.index') }}" class="btn muted">{{ __('admin.cancel') }}</a>
        </div>
      </form>
    </div>

    <div class="preview-column">
      <div class="tabs">
        <button class="tab active" data-preview-tab="ar">{{ __('admin.legal_pages.preview_ar') }}</button>
        <button class="tab" data-preview-tab="en">{{ __('admin.legal_pages.preview_en') }}</button>
      </div>

      <div class="preview-box" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
        <div class="preview-content" data-live-preview="ar">{!! nl2br(e($page->content['ar'] ?? '')) !!}</div>
        <div class="preview-content" data-live-preview="en" style="display:none">{!! nl2br(e($page->content['en'] ?? '')) !!}</div>
      </div>
    </div>
  </div>
</x-admin.container>
