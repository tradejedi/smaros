<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $view->with([
                'logoText' => 'Шмарвиль'
            ]);
        });

        View::composer(['components.left-menu'], function ($view) {
            $view->with([
                'sidebarMenu' => [
                    [
                        ['name' => 'Категории', 'url' => '/home', 'icon' => 'home'],
                        ['name' => 'Карта', 'url' => '/about', 'icon' => 'about'],
                    ],
                    [
                        ['name' => 'Список услуг', 'url' => '/services', 'icon' => 'services']
                    ]
                ],

            ]);
        });

        View::composer('components.partials.footer', function ($view) {
            $view->with([
                'footerMenu' => [
                    ['name' => 'Категории', 'url' => '/home', 'icon' => 'home'],
                    ['name' => 'Карта', 'url' => '/about', 'icon' => 'about'],
                ]
            ]);
        });
    }
}
