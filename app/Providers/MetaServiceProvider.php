<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class MetaServiceProvider extends ServiceProvider
{

    public function boot(): void
    {
        $metaData = [
            'default' => [
                'title' => 'Default Title',
                'description' => 'Default description',
                'keywords' => 'default, keywords',
            ],
            'home' => [
                'title' => 'Home Page',
                'description' => 'Welcome to our homepage',
                'keywords' => 'home, welcome',
            ],
            'products.index' => [
                'title' => 'Products',
                'description' => 'Browse our product catalog',
                'keywords' => 'products, catalog',
            ],
        ];

        // Передача данных во все шаблоны
        View::composer('*', function ($view) use ($metaData) {
            $routeName = optional(request()->route())->getName() ?? 'default';
            $view->with('meta', $metaData[$routeName] ?? $metaData['default']);
        });
    }
}
