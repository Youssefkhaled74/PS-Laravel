@if ($errors->any())
  <div class="p-3 mb-4 bg-red-900 rounded">
    <ul class="list-disc list-inside text-sm">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

@if (session('status'))
  <div class="p-3 mb-4 bg-green-900 rounded">{{ session('status') }}</div>
@endif
