<?php

namespace App\Console\Commands;

use App\Models\ContactTypes;
use App\Models\Role;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImportRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'website:import-roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $roles = collect(Storage::disk('seeders')->json('roles.json'));
        $roles->each(function ($contact) {
            Role::create([
                'name' => $contact['name'],
                'slug' => Str::slug($contact['name']),
                'description' => '',
            ]);
        });
    }
}
