@props([
    'user',
    'title' => 'Profile',
    'backUrl' => null,
    'showActions' => true,
    'addressesCount' => null,
])

@php
    $locale = app()->getLocale();
    $dir = in_array($locale, ['ar', 'he', 'fa']) ? 'rtl' : 'ltr';
    $initials = collect(explode(' ', $user->full_name ?? $user->name ?? 'U'))->map(fn($w) => mb_substr($w, 0, 1))->take(2)->join('');
    $isActive = $user->is_active ?? true;
    $statusLabel = $isActive ? __('Active') : __('Inactive');
    $statusClass = $isActive ? 'ps-badge-success' : 'ps-badge-muted';
@endphp

<div class="ps-profile-card" dir="{{ $dir }}">
  <div class="card">
    <div class="ps-profile-header">
      <div class="ps-avatar-wrapper">
        @if(!empty($user->avatar))
          <img src="{{ asset($user->avatar) }}" alt="{{ $user->full_name }}" class="ps-avatar">
        @else
          <div class="ps-avatar ps-avatar-initials">{{ $initials }}</div>
        @endif
      </div>
      
      <div class="ps-profile-title">
        <h2 class="h1">{{ $user->full_name ?? $user->name ?? __('User') }}</h2>
        <div class="ps-badges">
          <span class="ps-badge {{ $statusClass }}">{{ $statusLabel }}</span>
          @if($user->email_verified_at ?? false)
            <span class="ps-badge ps-badge-verified">✓ {{ __('Verified') }}</span>
          @endif
        </div>
      </div>
    </div>

    <div class="ps-profile-grid">
      <div class="ps-kv">
        <div class="ps-kv-icon">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
            <polyline points="22,6 12,13 2,6"/>
          </svg>
        </div>
        <div class="ps-kv-content">
          <div class="ps-kv-label">{{ __('Email') }}</div>
          <div class="ps-kv-value">
            <a href="mailto:{{ $user->email }}" class="ps-link">{{ $user->email }}</a>
          </div>
        </div>
        @if($showActions)
          <button type="button" class="ps-copy-btn btn-sm btn-ghost" data-copy="{{ $user->email }}" title="{{ __('Copy email') }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
              <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
            </svg>
          </button>
        @endif
      </div>

      <div class="ps-kv">
        <div class="ps-kv-icon">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
          </svg>
        </div>
        <div class="ps-kv-content">
          <div class="ps-kv-label">{{ __('Phone') }}</div>
          <div class="ps-kv-value">
            @php
              $phone = ($user->country_code ?? '') . ($user->phone ?? '');
            @endphp
            @if($phone)
              <a href="tel:{{ $phone }}" class="ps-link">{{ $phone }}</a>
            @else
              <span class="muted">{{ __('Not provided') }}</span>
            @endif
          </div>
        </div>
        @if($showActions && $phone)
          <button type="button" class="ps-copy-btn btn-sm btn-ghost" data-copy="{{ $phone }}" title="{{ __('Copy phone') }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
              <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
            </svg>
          </button>
        @endif
      </div>

      <div class="ps-kv">
        <div class="ps-kv-icon">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
            <line x1="16" y1="2" x2="16" y2="6"></line>
            <line x1="8" y1="2" x2="8" y2="6"></line>
            <line x1="3" y1="10" x2="21" y2="10"></line>
          </svg>
        </div>
        <div class="ps-kv-content">
          <div class="ps-kv-label">{{ __('Joined') }}</div>
          <div class="ps-kv-value">{{ $user->created_at ? $user->created_at->format('M d, Y') : '-' }}</div>
        </div>
      </div>

      @if($addressesCount !== null)
        <div class="ps-kv">
          <div class="ps-kv-icon">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
              <circle cx="12" cy="10" r="3"/>
            </svg>
          </div>
          <div class="ps-kv-content">
            <div class="ps-kv-label">{{ __('Addresses') }}</div>
            <div class="ps-kv-value">
              @if($addressesCount > 0)
                <a href="#addresses-section" class="ps-link">{{ $addressesCount }} {{ __('addresses') }}</a>
              @else
                <span class="muted">0</span>
              @endif
            </div>
          </div>
        </div>
      @endif

      @if($user->last_login_at ?? false)
        <div class="ps-kv">
          <div class="ps-kv-icon">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="10"/>
              <polyline points="12 6 12 12 16 14"/>
            </svg>
          </div>
          <div class="ps-kv-content">
            <div class="ps-kv-label">{{ __('Last Login') }}</div>
            <div class="ps-kv-value">{{ $user->last_login_at->diffForHumans() }}</div>
          </div>
        </div>
      @endif
    </div>

    @if($showActions)
      <div class="ps-actions">
        @if($backUrl)
          <a href="{{ $backUrl }}" class="btn btn-ghost">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:.35rem">
              <line x1="19" y1="12" x2="5" y2="12"></line>
              <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            {{ __('Back') }}
          </a>
        @endif
        
        @if($addressesCount > 0)
          <a href="#addresses-section" class="btn btn-ghost">
            {{ __('View Addresses') }}
          </a>
        @endif
      </div>
    @endif
  </div>
</div>
