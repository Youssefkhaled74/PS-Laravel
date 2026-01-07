@props(['items' => []])

<dl class="kv-list">
  @foreach($items as $label => $value)
    <div class="kv-row">
      <dt class="kv-label">{{ $label }}</dt>
      <dd class="kv-value">{!! $value !!}</dd>
    </div>
  @endforeach
</dl>
