<?php

namespace Database\Seeders;

use App\Services\ImportCityMetroDistrictDataService;
use Database\Seeders\Models\AttributeSeeder;
use Database\Seeders\Models\CommentSeeder;
use Database\Seeders\Models\ContactSeeder;
use Database\Seeders\Models\PageSeeder;
use Database\Seeders\Models\ProfileSeeder;
use Database\Seeders\Models\UserSeeder;
use Illuminate\Database\Seeder;
use seeders\SeoKeysSeeder;
use seeders\SeoModelsSeeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    protected $importCityMetroDistrictDataService;

    public function __construct(ImportCityMetroDistrictDataService $importCityMetroDistrictDataService)
    {
        $this->importCityMetroDistrictDataService = $importCityMetroDistrictDataService;
    }
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Вызов сидеров для пользователей, городов, характеристик и анкет
        $this->call(UserSeeder::class);
        $this->call(ProfileSeeder::class);
        $this->call(AttributeSeeder::class);
        $this->call(CommentSeeder::class);
        //$this->call(PhotoSeeder::class);
        $this->call(ContactSeeder::class);
        $this->call(PageSeeder::class);
    }
}
