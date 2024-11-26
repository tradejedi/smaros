<?php

namespace CoolmacJedi\SeoMetaManager\Providers;

use CoolmacJedi\SeoMetaManager\Services\SeoPageResolver;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use CoolmacJedi\SeoMetaManager\Services\SeoMetaService;
use CoolmacJedi\SeoMetaManager\Console\Commands\ImportSeoCommand;

class SeoMetaServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Регистрируем сервис в контейнере
        $this->app->singleton(SeoMetaService::class, function ($app) {
            return new SeoMetaService();
        });
        $this->app->singleton(SeoPageResolver::class, function ($app) {
            return $app->make(SeoPageResolver::class);
        });
    }

    public function boot()
    {
        // 1) Публикация config/seometa.php
        $this->publishes([
            __DIR__.'/../../config/seometa.php' => config_path('seometa.php'),
        ], 'seometamanager-config');

        // 2) Публикация view (шаблонов)
        $this->publishes([
            __DIR__.'/../../resources/views' => resource_path('views/vendor/seometamanager'),
        ], 'seometamanager-views');

        // 3) Публикация JSON-файла (например, дефолтного)
        $this->publishes([
            __DIR__.'/../../storage/seo/seo-defaults.json' => storage_path('app/seometamanager/seo-defaults.json'),
        ], 'seometamanager-storage');

        $this->publishes([
            __DIR__.'/../../database/migrations' => database_path('migrations'),
        ], 'seometamanager-migrations');

        // Подключаем вью из пакета (чтобы можно было использовать seometamanager::seo-meta)
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'seometamanager');

        // Регистрируем Artisan-команду
        /*if ($this->app->runningInConsole()) {
            $this->commands([
                ImportSeoCommand::class,
            ]);
            if(
                Schema::hasTable('seo_keys') &&
                Schema::hasTable('seo_models') &&
                Schema::hasTable('seo_values') &&
                Schema::hasTable('seo_model_templates')) {

                Artisan::call('seo:import');
            }
        }*/
    }
}
