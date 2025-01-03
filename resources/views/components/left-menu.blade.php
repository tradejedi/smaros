<div class="flex flex-col h-full overflow-y-auto pl-4 pr-2.5">
    @foreach($sidebarMenu as $item)
        <ul class="space-y-3">
        @foreach($item as $subItem)
            <li><a href="{{ $subItem['url'] }}" class="group flex items-center gap-2 p-2 w-full rounded-xl text-gray-600 dark:text-gray-300">
                    <span :class="{ 'hidden': isCollapsed, 'inline': !isCollapsed }" class="text-sm font-medium truncate">{{ $subItem['name'] }}</span>
                </a></li>
        @endforeach
        </ul>
        <hr class="my-4 border-gray-300 dark:border-zinc-700">
    @endforeach
</div>
