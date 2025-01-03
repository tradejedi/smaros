<aside :class="{ 'w-[110px]': isCollapsed, 'w-[256px]': !isCollapsed }" class="border-r border-gray-200 dark:border-zinc-600 bg-white dark:bg-zinc-800 fixed top-0 left-0 bottom-0 z-50 transition-all">
    <div class="flex flex-col h-full bg-white dark:bg-zinc-800">
        <!-- Logo -->
        <div class="pl-6 pr-4 flex justify-between items-center h-16">
            <x-logo />
            <x-collapse-button></x-collapse-button>
        </div>
        <!-- Menu Items -->
        <x-left-menu></x-left-menu>
    </div>
</aside>
