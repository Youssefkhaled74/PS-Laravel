@php $locale = session('locale', app()->getLocale()); $dir = $locale === 'ar' ? 'rtl' : 'ltr'; @endphp
<!doctype html>
<html lang="{{ $locale }}" dir="{{ $dir }}">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>{{ $title }}</title></head>
<body style="font-family:Arial,Helvetica,sans-serif;padding:2rem;">
    <h1>{{ $title }}</h1>
    <p>This is a placeholder for the {{ $title }} page. You can replace this with content from the database or a controller view.</p>
    <p><a href="{{ route('home') }}">Back to home</a></p>
</body>
</html>
