@php $rtl = app()->getLocale() === 'ar'; @endphp
<x-admin.container>
  <div class="page-header">
    <div class="page-header__left">
      <div class="page-header__icon">📚</div>
      <div>
        <h1 class="h1">{{ __('admin.legal_pages.title') }}</h1>
        <div class="small muted">{{ __('admin.legal_pages.subtitle') }}</div>
      </div>
    </div>
    <div class="page-header__right">
      <nav class="breadcrumb small muted">
        <a href="{{ route('admin.dashboard') }}">{{ __('admin.sidebar.dashboard') }}</a>
        <span> / </span>
        <span>{{ __('admin.legal_pages.title') }}</span>
      </nav>
    </div>
  </div>

  @if($pages->isEmpty())
    <div class="card">
      <div class="card-body">
        <div class="h3">{{ __('admin.legal_pages.empty_title') ?? __('admin.legal_pages.title') }}</div>
        <div class="p muted">{{ __('admin.legal_pages.empty_hint', ['link' => '']) }}</div>
      </div>
    </div>
  @else
    <div class="cms-grid">
      @foreach($pages as $page)
        <x-admin.cms-card
          :title="$page->title[app()->getLocale()] ?? $page->title['en'] ?? $page->key"
          :status="$page->status"
          :updatedAt="$page->updated_at"
          :version="$page->version"
          :editUrl="route('admin.legal-pages.edit', $page->key)"
          :previewUrl="route('admin.legal-pages.preview', $page->key) . '?lang=' . app()->getLocale()"
          icon="📄"
        />
      @endforeach
    </div>
  @endif
</x-admin.container>
