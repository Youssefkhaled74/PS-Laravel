@php $isRtl = app()->getLocale() === 'ar'; @endphp
<x-admin.container>
  <div class="row">
    <div class="col-12">
      <h2 class="h2">{{ __('admin.legal_pages.title') }}</h2>
      <div class="small">{{ __('admin.legal_pages.subtitle') ?? '' }}</div>
    </div>
  </div>

  <div class="grid gap">
    @foreach($pages as $page)
      <div class="card">
        <div class="card-body">
          <div class="card-title">{{ $page->title[app()->getLocale()] ?? $page->title['en'] ?? $page->key }}</div>
          <div class="small muted">
            <span class="badge {{ $page->status === 'published' ? 'badge-success' : 'badge-muted' }}">{{ __('admin.legal_pages.' . $page->status) }}</span>
            <span class="ml">{{ __('admin.created_at') }}: {{ $page->updated_at->diffForHumans() }}</span>
          </div>
          <div class="mt">
            <a href="{{ route('admin.legal-pages.edit', $page->key) }}" class="btn">{{ __('admin.legal_pages.edit') }}</a>
          </div>
        </div>
      </div>
    @endforeach
  </div>
</x-admin.container>
