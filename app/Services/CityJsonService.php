<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CityJsonService
{
    private $areas;
    private $filePath = 'areas.json';
    private $regions = [
        'Москва' => 'Московская область',
        'Санкт-Петербург' => 'Ленинградская область',
        'Екатеринбург' => 'Свердловская область',
        'Казань' => 'Республика Татарстан',
        'Нижний Новгород' => 'Нижегородская область',
        'Новосибирск' => 'Новосибирская область',
        'Самара' => 'Самарская область',
    ];

    public function __construct()
    {
        $this->areas = $this->fetchAreas();
    }

    /**
     * @return string[]
     */
    public function getRegions(): array
    {
        return $this->regions;
    }

    // Загрузка данных из API или файла
    private function fetchAreas(): array
    {
        if (Storage::exists($this->filePath)) {
            $lastModified = Storage::lastModified($this->filePath);
            if (now()->timestamp - $lastModified < 86400) { // Меньше суток
                return json_decode(Storage::get($this->filePath), true);
            }
        }

        $response = Http::get('https://api.hh.ru/areas');
        $data = $response->json();

        // Сохраняем данные с поддержкой кириллицы
        Storage::put($this->filePath, json_encode($data, JSON_UNESCAPED_UNICODE));

        return $data;
    }


    public function findSubCitiesByMainCityName(string $mainCityName): array
    {
        // Рекурсивный поиск города по имени
        $mainCity = $this->searchCityByName($this->areas, $mainCityName);

        // Если город не найден или не имеет подгородов, возвращаем пустой массив
        if (!$mainCity || empty($mainCity['areas'])) {
            return [];
        }

        // Собираем имена всех подгородов
        return array_map(function ($area) use ($mainCityName) {
            $area['name'] = str_replace($mainCityName, '', $area['name']);
            return Str::of($area['name'])
                ->before(',')
                ->replace([$mainCityName, '(', ')'], '')
                ->trim()
                ->value();

        }, $mainCity['areas']);
    }

    private function searchCityByName(array $areas, string $cityName): ?array
    {
        foreach ($areas as $area) {
            if (strcasecmp($area['name'], $cityName) === 0) {
                return $area;
            }
            if (!empty($area['areas'])) {
                $result = $this->searchCityByName($area['areas'], $cityName);
                if ($result) {
                    return $result;
                }
            }
        }
        return null;
    }

}
