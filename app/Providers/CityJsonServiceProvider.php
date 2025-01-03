<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\CityJsonService;

class CityJsonServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(CityJsonService::class, function ($app) {
            return new CityJsonService();
        });
    }

}
