
<div class="py-4">

    <!--
         1. Блок "Имя" (слева) + "Контакты" (справа на десктопе),
            на мобильном -> контакты идут следом
        -->
    <div class="md:flex md:justify-between md:items-start space-y-4 md:space-y-0">
        <!-- Имя -->
        <div class="md:flex-1">
            <h1 class="text-2xl font-bold">{{ $profile->name }}</h1>
        </div>

        <!-- Контакты (кнопки) -->
        <div class="md:flex md:space-x-2">
            <x-blocks.profile-contacts :contacts="$profile->contacts" />
        </div>
    </div>
    <!-- / Имя + Контакты -->

    <div class="my-4 border-b border-gray-300"></div>

    <!--
     2. Блок фотографий (слайдер) и справа атрибуты (список) + теги
        На мобильных блоки идут вертикально,
        на md - флекс/грид, фото слева, атрибуты + теги справа
    -->
    <div class="md:flex md:items-start md:space-x-8">
        <x-blocks.profile-slider />

        <!-- Атрибуты из списка + теги (справа на десктоп) -->
        <div class="md:w-1/3 space-y-4">
            <!-- Блок атрибутов (список: "massage", "striptease" и т.п.) -->
            <div class="p-4 bg-white">
                <div class="mt-2">
                    <ul class="list-disc list-inside text-sm">
                        @foreach($profileAttributes['variants'] as $attr)
                            <li>{{ $attr['name'] }}: {{ $attr['value'] }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Блок теги (ключевые слова) -->
            <div class="p-4 bg-white">
                <h2 class="text-lg font-semibold">Теги</h2>
                <div class="space-x-2">
                    <span class="inline-block bg-gray-200 px-2 py-1 rounded">#relax</span>
                    <span class="inline-block bg-gray-200 px-2 py-1 rounded">#vip</span>
                    <!-- ... -->
                </div>
            </div>
        </div>
    </div>
    <!-- / Фотографии + (Атрибуты + теги) -->

    <div class="my-4 border-b border-gray-300"></div>

    <!--
     3. Блок "Район, метро" (одна строка или один за другим)
        + "Описание" на всю ширину
    -->
    <div>
        <!-- Район, метро -->
        <div class="p-4">Метро:
            @foreach($metro as $station)
                <a href="{{ route('profiles.byMetro', ['slug' => $station['slug']]) }}">
                    {{ $station['name'] }}
                </a>
            @endforeach
        </div>

        <!-- Описание (развёрнутый текст) -->
        <div class="p-4">
            {{ $profile->description }}
        </div>
    </div>

    <div class="my-4 border-b border-gray-300"></div>

    <!--
     4. Блок с атрибутами да/нет (boolean) в 2 колонки,
        но на мобильном можно сделать их в 1 колонку
    -->
    <div class="grid grid-cols-2 md:grid-cols-2 gap-4 p-4">
        @foreach($profileBooleanAttributes as $group => $attributes)
            <div class="attribute-group">
                <h4>{{ ucfirst($group) }}</h4>
                <ul>
                    @foreach($attributes as $attribute)
                        <li>
                            <label>{{ $attribute['name'] }}:</label> {{ $attribute['value'] }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>
    <!-- / Атрибуты да/нет -->

    <div class="my-4 border-b border-gray-300"></div>

    <!--
     5. Блок Видео + Блок Цены (на мобильном - вертикально,
        на десктопе можно сделать flex/grid)
    -->
    <div class="grid grid-cols-2 gap-2 p-4">
        @foreach($prices as $price)
            <div>
                <label class="block font-medium">{{ $price['label'] }}</label>
                <span class="text-gray-700">{{ $price['amount'] }} руб.</span>
            </div>
        @endforeach
    </div>

    <div class="my-4 border-b border-gray-300"></div>

    <!-- 6. Блок Комментарии (Отзывы) -->
    <div class="bg-white p-4">
        <h2 class="text-lg font-semibold">Комментарии</h2>
        @foreach($comments as $comment)
        <div class="border-b border-gray-200 pb-2">
            <p class="mb-1">{{ $comment->comment }}</p>
            <small class="text-gray-500">Автор: {{ $comment->user->name }}:, {{ $comment->created_at->format('d.m.Y H:i') }}</small>
        </div>
        @endforeach
        <!-- ... -->
    </div>

    <div class="my-4 border-b border-gray-300"></div>

    <!-- 7. Похожие анкеты (из этого района) -->
    <div class="space-y-4">
        <h2 class="text-lg font-semibold">Похожие анкеты в этом районе</h2>
        <!-- Карточки анкет (упрощённый вид) -->
        <x-blocks.profile-list :profiles="$similarProfiles" />
    </div>

</div>

