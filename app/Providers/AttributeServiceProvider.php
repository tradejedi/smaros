<?php

namespace App\Providers;

use App\Services\ProfileAttributeService;
use Illuminate\Support\ServiceProvider;

class AttributeServiceProvider extends ServiceProvider
{
    /**
     * Регистрируем сервис.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ProfileAttributeService::class, function () {
            return new ProfileAttributeService();
        });
    }

    /**
     * Выполняем действия после регистрации.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
