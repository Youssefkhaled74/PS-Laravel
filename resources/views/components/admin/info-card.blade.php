@props(['title','subtitle'=>null])

<div class="card info-card">
  <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;gap:1rem">
    <div>
      <div class="h2">{{ $title }}</div>
      @if($subtitle)
        <div class="small">{{ $subtitle }}</div>
      @endif
    </div>
    <div class="card-actions">
      {{ $actions ?? '' }}
    </div>
  </div>

  <div class="card-body">
    {{ $slot }}
  </div>
</div>
