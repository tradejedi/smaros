<div class="max-w-md mx-auto bg-white overflow-hidden md:max-w-2xl border border-gray-200">
    <div class="md:flex">
        <!-- Фото анкеты -->
        <div class="md:shrink-0">
            <img class="h-48 w-full object-cover md:h-full md:w-48" src="https://avatars.mds.yandex.net/get-kinopoisk-image/1777765/43ad37ff-34c4-4ebc-9f0a-2f2eaa41a18d/220x330" alt="Profile Photo">
        </div>
        <div class="p-4">
            <!-- Имя -->
            <div class="uppercase tracking-wide text-lg font-semibold">
                <a href="{{ $profileLink }}">{{ $profile->id }} - {{ $profile->name }}</a>
            </div>

            <!-- Станция метро -->
            <div class="text-sm text-gray-500 mt-2">Станция метро: <span class="font-medium">{!! $metroStationsLinks !!}</span></div>

            <!-- Список услуг -->
            <div class="mt-3">
                <div class="text-sm font-medium text-gray-700 mb-1">Услуги:</div>
                <div class="flex flex-wrap gap-2">
                    @foreach ($randomAttributes as $attribute)
                        <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">{{ $attribute['value']['name'] }}</span>
                    @endforeach
                </div>
            </div>

            <!-- Цены -->
            <div class="mt-4">
                <div class="text-sm font-medium text-gray-700 mb-1">Цены:</div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="text-xs text-gray-500">Час у тебя:</span>
                        <span class="block text-lg font-semibold">{{ $prices['den-1-cas-u-tebia'] }} ₽</span>
                    </div>
                    <div>
                        <span class="text-xs text-gray-500">Вся ночь:</span>
                        <span class="block text-lg font-semibold">{{ $prices['vsia-noc-u-tebia'] }} ₽</span>
                    </div>
                </div>
            </div>

            <!-- Кнопки мессенджеров -->
            <div class="mt-4 flex gap-3">
                <x-blocks.profile-contacts :contacts="$profile->contacts" />
            </div>
        </div>
    </div>
</div>
