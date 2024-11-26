<?php

namespace App\Services;

use App\Models\City;
use App\Models\District;
use App\Models\MetroStation;
use App\Models\SubCities;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use function Symfony\Component\String\s;

class ImportCityMetroDistrictDataService
{
    private CityJsonService $cityJsonService;

    function __construct(CityJsonService $cityJsonService)
    {
        $this->cityJsonService = $cityJsonService;
    }
    private string $filePath;

    public function setFileName(string $name): self
    {
        $this->filePath = "{$name}.json";
        return $this;
    }

    public function options(): Collection
    {
        $this->setFileName('option');
        return $this->getFileContent();
    }

    public function metros(): Collection
    {
        $this->setFileName('metro');
        $metros = $this->getFileContent();
        $metros = $this->transformMetros($metros);
        return $metros;
    }

    public function subCities()
    {
        $regions = collect($this->cityJsonService->getRegions());
        $subCities = collect();
        $regions->each(function ($region, $city) use ($subCities) {
            $subCities->put($city, $this->cityJsonService->findSubCitiesByMainCityName($region));
        });

        return $subCities;
    }

    public function cities(): Collection
    {
        $metros = $this->metros();
        return $metros->keys();
    }

    public function districts(): Collection
    {
        $this->setFileName('district');
        return collect($this->getFileContent());
    }

    private function transformMetros(Collection $metros): Collection
    {
        return $metros->mapWithKeys(function (array $item) {
            $lines = collect($item['lines'])
                ->flatMap(fn($line) => collect($line['stations'])->pluck('name'));

            return [$item['name'] => $lines];
        });
    }

    public function open(string $key): Collection
    {
        return $this->$key()->$key;
    }


    private function getFileContent(): Collection
    {
        return collect(Storage::disk('seeders')->json($this->filePath));
    }

    protected function importCities(): void
    {
        $cities = $this->cities();
        foreach ($cities as $cityData) {
            City::updateOrCreate(
                ['name' => $cityData],
                ['slug' => Str::slug($cityData)]
            );
        }
    }

    protected function importSubCities(): void
    {
        $cities = City::all()->pluck('id', 'name');
        $subCities = $this->subCities();

        foreach ($subCities as $mainCity => $subCity) {
            if(!isset($cities[$mainCity])) {
                continue;
            }
            foreach ($subCity as $city) {
                SubCities::updateOrCreate(
                    ['name' => $city], // Критерии поиска
                    [
                        'slug' => Str::slug($city),  // Данные для обновления/создания
                        'city_id' => $cities[$mainCity] // Добавлен city_id
                    ]
                );
            }
        }
    }

    protected function importMetro(): void
    {
        $metros = $this->metros();
        City::all()->each(function ($city) use ($metros) {
            $metros[$city->name]->each(function ($metro) use ($city) {
                MetroStation::updateOrCreate([
                    'name' => $metro,
                    'city_id' => $city->id,
                    'slug' => Str::slug($metro)
                ]);
            });
        });
    }

    protected function importDistricts(): void
    {
        $districts = $this->districts();
        City::all()->each(function ($city) use ($districts) {
            $district = collect($districts[$city->name]);
            $district->each(function ($district) use ($city) {
                District::updateOrCreate([
                    'name' => $district,
                    'slug' => Str::slug($district),
                    'city_id' => $city->id,
                ]);
            });
        });
    }

    public function import()
    {
        $this->importCities();
        $this->importSubCities();
        $this->importMetro();
        $this->importDistricts();
    }
}
