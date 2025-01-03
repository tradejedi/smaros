<header class="sticky top-0 bg-white dark:bg-zinc-700 z-10">
    <div class="border-b border-gray-200 dark:border-zinc-600">
        <div class="flex items-center justify-between py-3 px-6">
            <div class="flex-grow flex items-center justify-end lg:justify-between">
                <!-- Search Bar -->
                <x-blocks.search-form-simple />
                <!-- User Actions -->
                <div class="flex items-center space-x-4">
                    @if (Route::has('login'))
                        <nav class="flex space-x-4">
                            @auth
                                <div class="hidden sm:flex sm:items-center sm:ms-6">
                                    <x-dropdown align="right" width="48">
                                        <x-slot name="trigger">
                                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                                <div>{{ ucfirst(Auth::user()->name) }}</div>

                                                <div class="ms-1">
                                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                            </button>
                                        </x-slot>

                                        <x-slot name="content">

                                            <x-dropdown-link :href="url('/dashboard')">
                                                {{ __('buttons.profile') }}
                                            </x-dropdown-link>

                                            <x-dropdown-link :href="route('profile.edit')">
                                                {{ __('buttons.settings') }}
                                            </x-dropdown-link>

                                            <!-- Authentication -->
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf

                                                <x-dropdown-link :href="route('logout')"
                                                                 onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                                    {{ __('Log Out') }}
                                                </x-dropdown-link>
                                            </form>
                                        </x-slot>
                                    </x-dropdown>
                                </div>
                            @else
                                <x-elements.button href="{{ route('login') }}" text="{{ __('buttons.login') }}" type="secondary" />
                                @if (Route::has('register'))
                                    <x-elements.button href="{{ route('register') }}" text="{{ __('buttons.register') }}" />
                                @endif
                            @endauth
                        </nav>
                    @endif
                </div>
            </div>
        </div>
    </div>
</header>
