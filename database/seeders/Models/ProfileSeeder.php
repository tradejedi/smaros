<?php

namespace Database\Seeders\Models;

use App\Models\City;
use App\Models\District;
use App\Models\MetroStation;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Получаем всех пользователей, которым будут добавляться профили
        $users = User::all();
        $cities = City::all();
        $metros = MetroStation::all();
        $districts = District::all();

        // Для каждого пользователя создаем от 1 до 3 профилей
        foreach ($users as $user) {
            $profileCount = rand(1, 3); // Количество профилей на одного пользователя
            $profiles = Profile::factory($profileCount)
                ->create([
                    'user_id' => $users->random()->id,
                    'city_id' => $cities->random()->id
                ]);
            $profiles->each(function ($profile) use ($metros, $districts) {
                // Привязка метро (много ко многим)
                $profile->metroStations()->attach($metros->random(rand(1, 3))->pluck('id')->toArray()); // Привязываем от 1 до 3 случайных станций метро

                // Привязка районов (много ко многим)
                $profile->districts()->attach($districts->random(rand(1, 3))->pluck('id')->toArray()); // Привязываем от 1 до 3 случайных районов
            });
        }
    }
}
