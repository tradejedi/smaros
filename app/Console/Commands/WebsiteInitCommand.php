<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class WebsiteInitCommand extends Command
{
    protected $signature = 'website:init';
    protected $description = 'Command description';
    public function handle()
    {
        Artisan::call('migrate:fresh');
        $this->info('Fresh all migrations');

        Artisan::call('website:import-city');
        $this->info('Import city, metro and districts');

        Artisan::call('website:import-attributes');
        $this->info('Import attributes');

        Artisan::call('website:import-contact-types');
        $this->info('Import contact types');

        Artisan::call('website:import-roles');
        $this->info('Import roles');

        Artisan::call('db:seed');
        $this->info('Fill databases');
    }
}
