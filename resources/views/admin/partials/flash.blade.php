@if ($errors->any())
  <div class="alert alert-danger" style="margin-bottom:1rem;">
    <div>⚠️</div>
    <div>
      <div style="font-weight:700;margin-bottom:.25rem;">
        {{ __('admin.flash.validation_error') ?? 'Validation error' }}
      </div>
      <ul style="margin:0;padding-inline-start:1.25rem;">
        @foreach ($errors->all() as $error)
          <li class="small" style="color:rgba(255,255,255,.85);">{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  </div>
@endif

@if (session('status'))
  <div class="alert alert-success" style="margin-bottom:1rem;">
    <div>✅</div>
    <div>{{ session('status') }}</div>
  </div>
@endif

@if (session('error'))
  <div class="alert alert-danger" style="margin-bottom:1rem;">
    <div>⛔</div>
    <div>{{ session('error') }}</div>
  </div>
@endif
