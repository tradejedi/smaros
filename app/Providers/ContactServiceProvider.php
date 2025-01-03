<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ContactButtonService;

class ContactServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Регистрируем ContactButtonService как singleton
        $this->app->singleton(ContactButtonService::class, function ($app) {
            return new ContactButtonService();
        });
    }

    public function boot(): void
    {
        //
    }
}
