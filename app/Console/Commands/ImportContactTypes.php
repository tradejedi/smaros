<?php

namespace App\Console\Commands;

use App\Models\Contact;
use App\Models\ContactTypes;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImportContactTypes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'website:import-contact-types';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Contacts Types';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $contacts = collect(Storage::disk('seeders')->json('contacts.json'));
        $contacts->each(function ($contact) {
            ContactTypes::create([
                'name' => $contact['name'],
                'slug' => Str::slug($contact['name'])
            ]);
        });
    }
}
