@extends('admin.layouts.app')

@section('content')
  <div class="card">
    <div class="toolbar">
      <div class="title-wrap">
        <h1 class="h1">{{ __('admin.admins.title') }}</h1>
        <div class="small p">{{ __('admin.admins.subtitle') ?? '' }}</div>
      </div>
      <div class="actions">
        <a href="{{ route('admin.admins.create') }}" class="btn btn-gold">{{ __('admin.admins.add') }}</a>
      </div>
    </div>

    <div class="card-soft">
      <form method="GET" action="{{ route('admin.admins.index') }}">
        <div class="filters">
          <input type="text" name="search" placeholder="{{ __('admin.admins.search_placeholder') }}" class="input" value="{{ request('search') }}">
          <select name="status" class="input">
            <option value="">{{ __('admin.filter') }}</option>
            <option value="active" {{ request('status')=='active' ? 'selected':'' }}>{{ __('admin.admins.active') }}</option>
            <option value="inactive" {{ request('status')=='inactive' ? 'selected':'' }}>{{ __('admin.admins.inactive') }}</option>
          </select>
          <select name="per_page" class="input">
            <option value="10" {{ request('per_page',10)==10? 'selected':''}}>10</option>
            <option value="15" {{ request('per_page',10)==15? 'selected':''}}>15</option>
            <option value="25" {{ request('per_page',10)==25? 'selected':''}}>25</option>
          </select>
          <div class="actions">
            <button class="btn btn-ghost btn-sm">{{ __('admin.filter') }}</button>
            @if(request()->has('search')||request()->has('status'))
              <a href="{{ route('admin.admins.index') }}" class="btn btn-ghost btn-sm">{{ __('admin.reset') }}</a>
            @endif
          </div>
        </div>
      </form>
    </div>

    <div class="mt-6 table-wrap">
      @if($admins->count())
        <table class="table">
          <thead>
            <tr>
              <th>#</th>
              <th>{{ __('admin.admins.name') }}</th>
              <th>{{ __('admin.admins.email') }}</th>
              <th>{{ __('admin.admins.status') }}</th>
              <th>{{ __('admin.actions') }}</th>
            </tr>
          </thead>
          <tbody>
            @foreach($admins as $a)
              <tr>
                <td>{{ $a->id }}</td>
                <td>{{ $a->name }}</td>
                <td>{{ $a->email }}</td>
                <td>
                  @if($a->status==='active')
                    <span class="badge-active small">{{ __('admin.admins.active') }}</span>
                  @else
                    <span class="badge-inactive small">{{ __('admin.admins.inactive') }}</span>
                  @endif
                </td>
                <td>
                  <div class="actions">
                    <a href="{{ route('admin.admins.edit', $a->id) }}" class="btn btn-ghost btn-sm">{{ __('admin.admins.edit') }}</a>
                    <form action="{{ route('admin.admins.toggle', $a->id) }}" method="POST" style="display:inline">
                      @csrf
                      @method('PATCH')
                      <button type="button" class="btn btn-success btn-sm js-confirm" data-confirm="{{ __('admin.admins.confirm_toggle') }}">{{ __('admin.admins.toggle') }}</button>
                    </form>
                    <form action="{{ route('admin.admins.destroy', $a->id) }}" method="POST" style="display:inline">
                      @csrf
                      @method('DELETE')
                      <button type="button" class="btn btn-danger btn-sm js-confirm" data-confirm="{{ __('admin.admins.confirm_delete') }}">{{ __('admin.delete') }}</button>
                    </form>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>

        <div class="pagination-wrap">{{ $admins->appends(request()->query())->links() }}</div>
      @else
        <div style="padding:2.5rem;text-align:center">
          <div class="h2">{{ __('admin.admins.empty') }}</div>
        </div>
      @endif
    </div>
  </div>
@endsection
