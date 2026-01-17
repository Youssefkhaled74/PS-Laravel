@extends('admin.layouts.app')

@section('content')
  <div class="page">
    <div class="page-header">
      <h1 class="h1">{{ __('admin.items.title') }}</h1>
      <div>
        <x-admin.table-filters :action="route('admin.items.index')" :showStatus="true" :showPerPage="true" :searchPlaceholder="__('admin.search')" />
      </div>
    </div>

    <div class="card">
      <table class="table">
        <thead>
          <tr>
            <th></th>
            <th>{{ __('admin.items.name') }}</th>
            <th>{{ __('admin.sidebar.vendors') }}</th>
            <th>{{ __('admin.price') ?? 'Price' }}</th>
            <th>{{ __('admin.status') }}</th>
            <th>{{ __('admin.created_at') }}</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @foreach($items as $item)
            <tr>
              <td>
                @if($item->images->first())
                  <img src="{{ asset($item->images->first()->path) }}" style="width:56px;height:56px;object-fit:cover;border-radius:8px" />
                @endif
              </td>
              <td>{{ $item->name }}</td>
              <td>{{ $item->vendor->name ?? '-' }}</td>
              <td>{{ number_format(($item->price ?? 0)/100,2) }}</td>
              <td>
                <span class="badge badge--{{ $item->status == 'approved' ? 'success' : ($item->status=='rejected' ? 'danger' : 'neutral') }}">{{ __('admin.items.status.' . $item->status) }}</span>
              </td>
              <td>{{ optional($item->created_at)->format('Y-m-d') }}</td>
              <td>
                <a href="{{ route('admin.items.show', $item->id) }}" class="btn btn-ghost btn-sm">{{ __('admin.items.actions.view') }}</a>
                @if($item->status !== 'approved')
                  <form action="{{ route('admin.items.approve', $item) }}" method="POST" style="display:inline" class="approve-form" data-item-id="{{ $item->id }}">
                    @csrf @method('PATCH')
                    <button type="button" class="btn btn-gold btn-sm" onclick="openConfirmModal('approve', {{ $item->id }})">{{ __('admin.items.actions.approve') }}</button>
                  </form>
                @endif
                @if($item->status !== 'rejected')
                  <button type="button" class="btn btn-ghost btn-sm" onclick="openConfirmModal('reject', {{ $item->id }})">{{ __('admin.items.actions.reject') }}</button>
                @endif
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>

      <div class="pagination-wrap">{{ $items->appends(request()->query())->links() }}</div>
    </div>

    <form id="reject-form" method="POST" style="display:none">
      @csrf @method('PATCH')
      <input type="hidden" name="rejection_reason" id="reject-reason" />
    </form>

    <!-- Custom confirm/reject modal -->
    <div id="confirm-modal" class="confirm-modal" style="display:none">
      <div class="confirm-overlay"></div>
      <div class="confirm-box">
        <div class="confirm-body">
          <p id="confirm-message" style="margin:0 0 12px"></p>
          <textarea id="confirm-reason" placeholder="{{ __('admin.items.reject_reason_placeholder') }}" style="width:100%;min-height:80px;display:none;margin-bottom:12px;padding:8px;border-radius:6px;border:1px solid #ccc"></textarea>
        </div>
        <div class="confirm-actions" style="text-align:right">
          <button type="button" class="btn btn-ghost" onclick="closeConfirmModal()">{{ __('admin.cancel') }}</button>
          <button type="button" id="confirm-ok" class="btn btn-primary" style="margin-left:8px">{{ __('admin.ok') }}</button>
        </div>
      </div>
    </div>

    <style>
      .confirm-modal { position:fixed; inset:0; z-index:2000; display:flex; align-items:center; justify-content:center }
      .confirm-overlay { position:absolute; inset:0; background:rgba(0,0,0,0.6); }
      .confirm-box { position:relative; background:#1f1f1f; color:#fff; padding:20px; border-radius:10px; width:420px; max-width:90%; box-shadow:0 8px 24px rgba(0,0,0,0.6) }
      .confirm-box p { color:#e6e6e6 }
      .btn-primary { background:#0b78a3; color:white; padding:8px 14px; border-radius:8px; border:none }
      .btn-ghost { background:transparent; color:#cfcfcf; padding:8px 12px; border-radius:8px; border:1px solid rgba(255,255,255,0.06) }
    </style>

    <script>
      var currentAction = null;
      var currentId = null;

      function openConfirmModal(action, id) {
        currentAction = action; currentId = id;
        var msgEl = document.getElementById('confirm-message');
        var reasonEl = document.getElementById('confirm-reason');
        if (action === 'approve') {
          msgEl.textContent = '{{ __('admin.items.actions.confirm_approve') }}';
          reasonEl.style.display = 'none';
        } else if (action === 'reject') {
          msgEl.textContent = '{{ __('admin.items.actions.confirm_reject') }}';
          reasonEl.style.display = 'block';
          reasonEl.value = '';
        }
        document.getElementById('confirm-modal').style.display = 'flex';
      }

      function closeConfirmModal() {
        document.getElementById('confirm-modal').style.display = 'none';
        currentAction = null; currentId = null;
      }

      document.getElementById('confirm-ok').addEventListener('click', function() {
        if (!currentAction || !currentId) return closeConfirmModal();
        if (currentAction === 'approve') {
          var form = document.querySelector('.approve-form[data-item-id="' + currentId + '"]');
          if (form) form.submit();
          closeConfirmModal();
          return;
        }
        if (currentAction === 'reject') {
          var reason = document.getElementById('confirm-reason').value.trim();
          if (!reason) {
            alert('{{ __('admin.items.reject_reason_required') }}');
            return;
          }
          var form = document.getElementById('reject-form');
          form.action = '/admin/items/' + currentId + '/reject';
          document.getElementById('reject-reason').value = reason;
          form.style.display = 'block';
          form.submit();
          closeConfirmModal();
        }
      });
    </script>
  </div>
@endsection
