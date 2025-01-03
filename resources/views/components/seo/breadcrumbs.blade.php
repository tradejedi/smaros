<nav class="bg-gray-100 border p-2 text-sm" aria-label="breadcrumb">
    <ol class="list-reset flex items-center space-x-2">
        @foreach($breadcrumbs as $crumb)
            <li>
                @if(!empty($crumb['url']))
                    <a href="{{ $crumb['url'] }}" class="text-blue-600 hover:underline">
                        {{ $crumb['title'] }}
                    </a>
                @else
                    <span class="text-gray-700">{{ $crumb['title'] }}</span>
                @endif
            </li>

            @if(!$loop->last)
                <li class="text-gray-400">/</li>
            @endif
        @endforeach
    </ol>
</nav>
