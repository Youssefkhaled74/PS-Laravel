@props([
  'addresses',
  'locale' => null,
  'showActions' => false,
  'setDefaultRouteName' => null,
  'editRouteName' => null,
])

@php
  $locale = $locale ?? app()->getLocale();
  $dir = in_array($locale, ['ar','he','fa']) ? 'rtl' : 'ltr';
  $addresses = collect($addresses ?? []);
  $default = $addresses->firstWhere('is_default', true);
@endphp

<section class="ps-address" dir="{{ $dir }}">
  <div class="card ps-address__card">
    <div class="ps-address__header">
      <div class="ps-address__title">
        <h3 class="h2">{{ __('Addresses') }}</h3>
        <span class="ps-address__count">{{ $addresses->count() }}</span>
      </div>
      @if($default)
        <span class="ps-badge ps-badge--success">{{ __('Default address') }}</span>
      @endif
    </div>

    @if($addresses->isEmpty())
      <div class="small ps-address__empty">{{ __('No addresses found.') }}</div>
    @else
      <div class="ps-address__grid">
        @foreach($addresses as $addr)
          @php
            $hasCoords = !empty($addr->lat) && !empty($addr->lng);
            $mapUrl = $hasCoords ? "https://www.google.com/maps?q={$addr->lat},{$addr->lng}" : null;
            $isDefault = (bool)($addr->is_default ?? false);

            $line1 = trim(implode(', ', array_filter([
              data_get($addr,'address_line'),
              data_get($addr,'district'),
              data_get($addr,'city')
            ])));

            $metaPieces = array_filter([
              $addr->building_no ? __('Building:') . ' ' . $addr->building_no : null,
              $addr->apartment_no ? __('Apt:') . ' ' . $addr->apartment_no : null,
              $addr->floor ? __('Floor:') . ' ' . $addr->floor : null,
            ]);
          @endphp

          <article class="ps-address__item">
            <div class="ps-address__top">
              <div class="ps-address__left">
                <div class="ps-address__icon" aria-hidden="true">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                </div>

                <div class="ps-address__info">
                  <div class="ps-address__nameRow">
                    <div class="ps-address__name">{{ $addr->title ?? __('Address') }}</div>
                    <span class="ps-badge {{ $isDefault ? 'ps-badge--success' : 'ps-badge--neutral' }}">
                      {{ $isDefault ? __('Default') : __('Secondary') }}
                    </span>
                  </div>

                  <div class="ps-address__line">{{ $line1 ?: '-' }}</div>

                  <div class="ps-address__meta">
                    @if(count($metaPieces))
                      <span>{{ implode(' · ', $metaPieces) }}</span>
                    @endif

                    @if(!empty($addr->notes))
                      <span class="ps-address__note">{{ $addr->notes }}</span>
                    @endif
                  </div>
                </div>
              </div>

              <div class="ps-address__actions">
                @if($hasCoords)
                  <a class="ps-btn ps-btn--primary" href="{{ $mapUrl }}" target="_blank" rel="noopener">
                    {{ __('Map') }}
                  </a>
                  <button type="button"
                          class="ps-btn ps-btn--ghost js-copy-map"
                          data-map-url="{{ $mapUrl }}">
                    {{ __('Copy') }}
                  </button>
                @else
                  <button class="ps-btn ps-btn--ghost" disabled>{{ __('No Location') }}</button>
                @endif

                @if($showActions && $setDefaultRouteName && ! $isDefault)
                  <form method="POST" action="{{ route($setDefaultRouteName, $addr->id) }}">
                    @csrf
                    <button type="submit" class="ps-btn ps-btn--ghost">
                      {{ __('Set Default') }}
                    </button>
                  </form>
                @endif

                @if($showActions && $editRouteName)
                  <a class="ps-btn ps-btn--ghost" href="{{ route($editRouteName, $addr->id) }}">
                    {{ __('Edit') }}
                  </a>
                @endif
              </div>
            </div>

            @if($hasCoords)
              <div class="ps-address__coords">
                <span>{{ __('Coordinates:') }}</span>
                <code>{{ $addr->lat }}, {{ $addr->lng }}</code>
              </div>
            @endif
          </article>
        @endforeach
      </div>
    @endif
  </div>

  {{-- Inline toast (optional, simple) --}}
  <div class="ps-toast" id="ps-toast" aria-live="polite" aria-atomic="true"></div>

  <style>
    /* ===== PS Address Compact UI (inline) ===== */
    .ps-address__card{ padding:1rem; }
    .ps-address__header{
      display:flex; align-items:center; justify-content:space-between; gap:.75rem;
      padding-bottom:.75rem; border-bottom:1px solid rgba(0,0,0,.08);
    }
    .ps-address__title{display:flex; align-items:center; gap:.6rem}
    .ps-address__count{
      display:inline-flex; align-items:center; justify-content:center;
      min-width:26px; height:26px; padding:0 .5rem;
      border-radius:999px; font-weight:800; font-size:.85rem;
      background:rgba(139,90,20,.10); color:#8B5A14;
    }
    .ps-address__empty{padding:.75rem 0}

    .ps-address__grid{
      margin-top:1rem;
      display:grid; gap:.9rem;
      grid-template-columns:repeat(2, minmax(0, 1fr));
    }
    @media (max-width: 980px){ .ps-address__grid{ grid-template-columns:1fr; } }

    .ps-address__item{
      border:1px solid rgba(0,0,0,.08);
      border-radius:14px;
      padding:.9rem;
      background:rgba(255,255,255,.65);
      box-shadow:0 10px 24px rgba(0,0,0,.05);
    }

    /* Dark mode support (if you add .theme-dark on body/html) */
    .theme-dark .ps-address__header{ border-bottom-color: rgba(255,255,255,.10); }
    .theme-dark .ps-address__item{
      background:rgba(10,10,10,.55);
      border-color: rgba(255,255,255,.10);
      box-shadow:0 14px 30px rgba(0,0,0,.35);
    }

    .ps-address__top{ display:flex; align-items:flex-start; justify-content:space-between; gap:1rem; }
    .ps-address__left{ display:flex; align-items:flex-start; gap:.8rem; min-width:0; flex:1; }
    .ps-address__icon{
      width:38px; height:38px; border-radius:12px;
      display:flex; align-items:center; justify-content:center;
      background:rgba(139,90,20,.12); color:#8B5A14;
      flex:0 0 auto;
    }

    .ps-address__info{min-width:0}
    .ps-address__nameRow{display:flex; align-items:center; gap:.5rem; flex-wrap:wrap}
    .ps-address__name{font-weight:900; font-size:1rem; color:rgba(0,0,0,.85)}
    .theme-dark .ps-address__name{ color:rgba(255,255,255,.92); }

    .ps-address__line{
      margin-top:.25rem;
      font-size:.92rem;
      color:rgba(0,0,0,.62);
      line-height:1.45;
      word-break:break-word;
    }
    .theme-dark .ps-address__line{ color:rgba(255,255,255,.70); }

    .ps-address__meta{
      margin-top:.35rem;
      display:flex;
      flex-direction:column;
      gap:.25rem;
      font-size:.85rem;
      color:rgba(0,0,0,.55);
    }
    .theme-dark .ps-address__meta{ color:rgba(255,255,255,.60); }
    .ps-address__note{ opacity:.9; }

    .ps-address__actions{
      display:flex;
      align-items:center;
      gap:.5rem;
      flex-wrap:wrap;
      justify-content:flex-end;
      flex:0 0 auto;
    }

    .ps-btn{
      border-radius:12px;
      padding:.45rem .75rem;
      font-weight:800;
      font-size:.88rem;
      border:1px solid rgba(0,0,0,.10);
      background:#fff;
      cursor:pointer;
      text-decoration:none;
      display:inline-flex; align-items:center; justify-content:center;
    }
    .theme-dark .ps-btn{
      background:rgba(255,255,255,.03);
      border-color: rgba(255,255,255,.14);
      color:rgba(255,255,255,.88);
    }

    .ps-btn--primary{
      background:linear-gradient(180deg, #a56a1d, #8B5A14);
      border-color: transparent;
      color:#fff;
      box-shadow:0 10px 20px rgba(139,90,20,.18);
    }
    .ps-btn--primary:hover{opacity:.96}
    .ps-btn--ghost{ background:transparent; }
    .ps-btn:disabled{ opacity:.55; cursor:not-allowed; }

    .ps-badge{
      display:inline-flex; align-items:center;
      padding:.2rem .55rem;
      border-radius:999px;
      font-weight:900;
      font-size:.78rem;
      border:1px solid rgba(0,0,0,.08);
      background:rgba(0,0,0,.03);
      color:rgba(0,0,0,.70);
    }
    .theme-dark .ps-badge{
      border-color: rgba(255,255,255,.14);
      background:rgba(255,255,255,.04);
      color:rgba(255,255,255,.75);
    }
    .ps-badge--success{
      background:rgba(34,197,94,.12);
      color:#16a34a;
      border-color: rgba(34,197,94,.22);
    }
    .ps-badge--neutral{ opacity:.85; }

    .ps-address__coords{
      margin-top:.65rem;
      padding-top:.65rem;
      border-top:1px dashed rgba(0,0,0,.12);
      display:flex; gap:.5rem; align-items:center;
      font-size:.82rem;
      color:rgba(0,0,0,.55);
      flex-wrap:wrap;
    }
    .theme-dark .ps-address__coords{
      border-top-color: rgba(255,255,255,.14);
      color:rgba(255,255,255,.60);
    }
    .ps-address__coords code{
      padding:.15rem .45rem;
      border-radius:8px;
      background:rgba(0,0,0,.04);
    }
    .theme-dark .ps-address__coords code{ background:rgba(255,255,255,.06); }

    /* Toast */
    .ps-toast{
      position:fixed;
      bottom:18px;
      inset-inline-start:18px;
      padding:.6rem .85rem;
      border-radius:12px;
      background:rgba(0,0,0,.85);
      color:#fff;
      font-weight:800;
      font-size:.9rem;
      opacity:0;
      transform:translateY(8px);
      transition:.18s ease;
      pointer-events:none;
      z-index:9999;
    }
    .ps-toast.is-show{ opacity:1; transform:translateY(0); }
  </style>

  <script>
    // inline small script (if you don't want to touch admin.js)
    document.addEventListener('click', function(e){
      const btn = e.target.closest('.js-copy-map');
      if (!btn) return;
      const url = btn.dataset.mapUrl;
      if (!url) return;

      navigator.clipboard.writeText(url).then(()=>{
        const toast = document.getElementById('ps-toast');
        if (!toast) return;
        toast.textContent = "{{ __('Copied') }}";
        toast.classList.add('is-show');
        setTimeout(()=>toast.classList.remove('is-show'), 1200);
      });
    });
  </script>
</section>
