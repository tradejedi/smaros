<?php

namespace Database\Seeders\Models;

use App\Models\Contact;
use App\Models\ContactTypes;
use App\Models\Profile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $profiles = Profile::all();
        $contactTypes = ContactTypes::all();

        $profiles->each(function ($profile) use ($contactTypes) {
            $count = rand(1, $contactTypes->count());

            for ($i = 0; $i < $count; $i++) {
                $contactType = $contactTypes->random();

                switch ($contactType->slug) {
                    case Str::slug("Телеграмм"):
                    case Str::slug("Instagram"):
                    case Str::slug("Poles"):
                        $value = '@' . fake()->word();
                    break;

                    case Str::slug("Телефон"):
                    case Str::slug("Viber"):
                        $value = fake()->phoneNumber();
                    break;

                }
                Contact::create([
                    'profile_id' => $profile->id,
                    'type_id' => $contactType->id,
                    'value' => $value,
                ]);
            }
        });
    }
}
