@props([
  'title' => '',
  'status' => 'published',
  'updatedAt' => null,
  'version' => null,
  'editUrl' => '#',
  'previewUrl' => '#',
  'icon' => '📄',
])

@php $rtl = app()->getLocale() === 'ar'; @endphp

<div class="cms-card card">
  <div class="cms-card__head">
    <div class="cms-card__icon">{{ $icon }}</div>
    <div class="cms-card__title">
      <div class="h3">{{ $title }}</div>
    </div>
    <div class="cms-card__status">
      @if($status === 'published')
        <span class="badge badge-published">{{ __('admin.legal_pages.published') }}</span>
      @else
        <span class="badge badge-draft">{{ __('admin.legal_pages.draft') }}</span>
      @endif
    </div>
  </div>

  <div class="cms-card__meta small muted">
    <div>{{ __('admin.legal_pages.last_updated') }}: {{ $updatedAt ? $updatedAt->diffForHumans() : '-' }}</div>
    @if($version)
      <div>{{ __('admin.legal_pages.version') }}: {{ $version }}</div>
    @endif
  </div>

  <div class="cms-card__actions">
    <a href="{{ $previewUrl }}" class="btn btn-ghost">{{ __('admin.preview') }}</a>
    <a href="{{ $editUrl }}" class="btn btn-gold">{{ __('admin.legal_pages.edit') }}</a>
  </div>
</div>
