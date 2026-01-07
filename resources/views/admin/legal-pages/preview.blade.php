@php $lang = request('lang', app()->getLocale()); @endphp
<div class="preview-page">
  @php
    $lines = preg_split('/\r?\n/', $content);
  @endphp

  @foreach($lines as $line)
    @if(trim($line) === '')
      <p></p>
    @elseif(str_starts_with(trim($line), '- '))
      @php $items[] = substr(trim($line), 2); @endphp
    @else
      <p>{{ $line }}</p>
    @endif
  @endforeach

  @if(! empty($items ?? []))
    <ul>
      @foreach($items as $it)
        <li>{{ $it }}</li>
      @endforeach
    </ul>
  @endif
</div>
