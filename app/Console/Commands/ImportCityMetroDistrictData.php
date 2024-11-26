<?php

namespace App\Console\Commands;

use App\Services\ImportCityMetroDistrictDataService;
use Illuminate\Console\Command;

class ImportCityMetroDistrictData extends Command
{
    protected $signature = 'website:import-city';
    protected $description = 'Command for import City, Metro, District data';
    protected $importCityMetroDistrictDataService;

    // Внедряем сервис импорта данных
    public function __construct(ImportCityMetroDistrictDataService $importCityMetroDistrictDataService)
    {
        parent::__construct();
        $this->importCityMetroDistrictDataService = $importCityMetroDistrictDataService;
    }

    // Логика выполнения команды
    public function handle()
    {
        // Вызываем метод для обработки JSON файлов
        #$this->importCityMetroDistrictDataService->importFromJson();
        $this->importCityMetroDistrictDataService->import();

        $this->info('Location data has been successfully imported!');
    }
}
