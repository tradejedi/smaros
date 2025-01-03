@if ($elementType === 'a')
    <a {{ $attributes->merge(['class' => $classes()]) }}>
        @if ($type === 'loading')
            <svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path d="M12 2a10 10 0 0110 10h-2a8 8 0 00-8-8V2z" stroke="currentColor" stroke-width="4" stroke-linecap="round"></path>
            </svg>
        @endif

        @if($type == 'icon')
            <x-dynamic-component :component="$icon" class="w-5 h-5" />
        @endif
        {{ $text }}
    </a>
@elseif ($elementType === 'button')
    <button {{ $attributes->merge(['class' => $classes()]) }}>
        @if ($type === 'loading')
            <svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path d="M12 2a10 10 0 0110 10h-2a8 8 0 00-8-8V2z" stroke="currentColor" stroke-width="4" stroke-linecap="round"></path>
            </svg>
        @endif
        {{ $text }}
    </button>
@elseif ($elementType === 'input')
    <input type="button" value="{{ $text }}" {{ $attributes->merge(['class' => $classes()]) }}">
@endif
