@props(['title','url'=>null,'status'=>null,'inputName'=>'file','accept'=>'image/*,application/pdf','preview'=>null])

<div class="file-tile">
  <div class="file-thumb">
    @if($preview)
      <img src="{{ asset($preview) }}" alt="{{ $title }}" />
    @elseif($url)
      <div class="file-icon">📄</div>
    @else
      <div class="file-icon">⬆️</div>
    @endif
  </div>
  <div class="file-meta">
    <div class="file-title">{{ $title }}</div>
    @if($url)
      <div class="file-actions">
        <a href="{{ asset($url) }}" target="_blank" class="btn btn-ghost btn-sm">{{ __('admin.view') }}</a>
      </div>
    @endif
    <div class="file-upload">
      <label class="btn btn-ghost btn-sm">
        {{ __('admin.replace') ?? 'Replace' }}
        <input type="file" name="{{ $inputName }}" accept="{{ $accept }}" style="display:none" />
      </label>
  </div>
  </div>
</div>
