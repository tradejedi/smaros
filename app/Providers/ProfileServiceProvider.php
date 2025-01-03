<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use \App\Services\ProfileAttributeService;
use \App\Services\ProfileService;

class ProfileServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ProfileService::class, function ($app) {
            return new ProfileService();
        });
    }
}
