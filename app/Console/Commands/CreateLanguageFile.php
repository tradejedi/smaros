<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CreateLanguageFile extends Command
{
    protected $signature = 'make:lang {locale} {name}';
    protected $description = 'Создаёт языковой файл в указанной локали';

    public function handle()
    {
        $locale = $this->argument('locale');
        $name = $this->argument('name');

        $path = resource_path("lang/{$locale}");

        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }

        $filePath = "{$path}/{$name}.php";

        if (File::exists($filePath)) {
            $this->error("Файл {$filePath} уже существует.");
            return;
        }

        File::put($filePath, "<?php\n\nreturn [\n    // Добавьте переводы здесь\n];\n");

        $this->info("Файл {$filePath} успешно создан.");
    }
}
