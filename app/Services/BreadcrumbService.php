<?php

namespace App\Services;

use Illuminate\Support\Facades\Route;

class BreadcrumbService
{
    /**
     * Получить хлебные крошки на основе имени текущего маршрута.
     *
     * @return array
     */
    public function getBreadcrumbs(): array
    {
        // Текущее имя роута (null, если нет маршрута)
        $routeName = optional(Route::current())->getName();

        switch ($routeName) {
            // ----------- Гостевая часть -----------
            case 'home':
                // Главная
                return [
                    ['title' => 'Главная'],
                ];

            case 'about':
                // О нас
                return [
                    ['title' => 'Главная', 'url' => route('home')],
                    ['title' => 'О нас'],
                ];

            case 'contacts':
                // Контакты
                return [
                    ['title' => 'Главная', 'url' => route('home')],
                    ['title' => 'Контакты'],
                ];

            case 'profiles.map':
                // Карта всех профилей
                return [
                    ['title' => 'Главная', 'url' => route('home')],
                    ['title' => 'Карта профилей'],
                ];

            case 'services.index':
                // Список всех услуг
                return [
                    ['title' => 'Главная', 'url' => route('home')],
                    ['title' => 'Услуги'],
                ];

            case 'services.profiles':
                // Все профили по категории (slug)
                // Получаем slug из URL, если нужно более гибкое название
                // например, "Массаж", "Экстрим", "SM" и т.п.
                $slug = Route::current()->parameter('slug');
                return [
                    ['title' => 'Главная', 'url' => route('home')],
                    ['title' => 'Услуги',   'url' => route('services.index')],
                    ['title' => "Профили по услуге: {$slug}"],
                ];

            case 'profiles.byMetro':
                // Все профили по станции метро
                $slug = Route::current()->parameter('slug');
                return [
                    ['title' => 'Главная', 'url' => route('home')],
                    ['title' => 'Станции метро'],
                    ['title' => "Профили у метро: {$slug}"],
                ];

            case 'profiles.byCity':
                // Все профили по городу
                $slug = Route::current()->parameter('slug');
                return [
                    ['title' => 'Главная', 'url' => route('home')],
                    ['title' => 'Города'],
                    ['title' => "Профили в городе: {$slug}"],
                ];

            case 'profiles.byDistrict':
                // Все профили по району
                $slug = Route::current()->parameter('slug');
                return [
                    ['title' => 'Главная', 'url' => route('home')],
                    ['title' => 'Районы'],
                    ['title' => "Профили в районе: {$slug}"],
                ];

            case 'search.advanced':
                // Страница расширенного поиска
                return [
                    ['title' => 'Главная', 'url' => route('home')],
                    ['title' => 'Расширенный поиск'],
                ];

            // ----------- Авторизованная часть -----------
            case 'profiles.create':
                // Добавление профиля
                return [
                    ['title' => 'Главная', 'url' => route('home')],
                    ['title' => 'Мои профили', 'url' => route('profiles.myList')],
                    ['title' => 'Добавить профиль'],
                ];

            case 'profiles.edit':
                // Редактирование профиля
                // Профиль передаётся обычно как {profile}, можно вывести его имя или ID
                // например: $profile = Route::current()->parameter('profile');
                return [
                    ['title' => 'Главная', 'url' => route('home')],
                    ['title' => 'Мои профили', 'url' => route('profiles.myList')],
                    ['title' => 'Редактировать профиль'],
                ];

            case 'profiles.myList':
                // Список всех профилей (для авторизованного пользователя)
                return [
                    ['title' => 'Главная', 'url' => route('home')],
                    ['title' => 'Мои профили'],
                ];

            case 'profiles.show':
                // Благодаря Route Model Binding, тут уже есть готовая модель.
                // Не делается новый запрос к БД — Laravel хранит модель в parameter('profile').
                $profile = Route::current()->parameter('profile');

                return [
                    ['title' => 'Главная', 'url' => route('home')],
                    ['title' => 'Список профилей', 'url' => route('profiles.myList')],
                    [
                        'title' => "{$profile->name}"
                        // или "Профиль: {$profile->name}"
                    ],
                ];

            // Добавьте другие маршруты (например, profiles.show) при необходимости

            default:
                // Если имя маршрута неизвестно — либо пустой массив, либо общий вариант
                return [
                    ['title' => 'Главная', 'url' => route('home')],
                    ['title' => 'Страница'],
                ];
        }
    }
}
