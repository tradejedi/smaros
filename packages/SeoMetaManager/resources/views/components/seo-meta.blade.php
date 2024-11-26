@props(['tags' => []])

@foreach($tags as $key => $value)
    @if($key === 'title')
        <title>{{ $value }}</title>
    @elseif($key === 'description')
        <meta name="description" content="{{ $value }}">
    @elseif(Str::startsWith($key, 'og_'))
        <meta property="og:{{ Str::after($key, 'og_') }}" content="{{ $value }}">
    @else
        <meta name="{{ $key }}" content="{{ $value }}">
    @endif
@endforeach
