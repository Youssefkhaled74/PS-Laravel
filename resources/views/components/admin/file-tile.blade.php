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
    <div class="file-upload" style="position:relative; display:inline-block;">
      <label class="btn btn-ghost btn-sm" style="position:relative; overflow:hidden;">
        {{ __('admin.replace') ?? 'Replace' }}
        <input type="file" name="{{ $inputName }}" accept="{{ $accept }}"
               style="position:absolute; inset:0; width:100%; height:100%; opacity:0; cursor:pointer;" />
      </label>
  </div>
  </div>
</div>
