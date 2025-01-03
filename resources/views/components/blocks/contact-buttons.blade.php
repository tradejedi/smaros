@foreach($buttons as $button)
    <x-elements.button href="{{ $button['url'] }}" type="icon" icon="{{ $button['icon'] }}" />
@endforeach
