<?php

namespace CoolmacJedi\SeoMetaManager\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

// Предположим, наши модели Eloquent лежат в App\Models
use CoolmacJedi\SeoMetaManager\Models\SeoKey;
use CoolmacJedi\SeoMetaManager\Models\SeoModelTemplate;
use Illuminate\Support\Facades\Storage;

class ImportSeoCommand extends Command
{
    protected $signature = 'seo:import';

    protected $description = 'Import SEO keys and model templates from a JSON file';

    public function handle()
    {
        // Убираем возможность добавлять path
        // Всегда берем файл seometamanager/seo-defaults.json на диске local
        $filePath = 'seometamanager/seo-defaults.json';

        // Проверяем существование
        if (! Storage::exists($filePath)) {
            $this->error("File not found in Storage: $filePath");
            return 1;
        }

        // Считываем содержимое
        $data = Storage::json($filePath);

        // Проверяем валидность JSON
        if (! is_array($data)) {
            $this->error("Invalid JSON in $filePath");
            return 1;
        }
        $this->importSeoData($data);

        $this->info("SEO data imported successfully from $filePath");

        return 0;
    }

    protected function importSeoData(array $data)
    {
        // 1) Импортируем глобальные ключи
        if (! empty($data['keys']) && is_array($data['keys'])) {
            foreach ($data['keys'] as $keyName => $item) {
                // item: { "global_template": "..." }
                $seoKey = SeoKey::firstOrNew(['key' => $keyName]);
                $seoKey->global_template = $item['global_template'] ?? null;
                $seoKey->save();
            }
        }

        // 2) Импортируем модельные шаблоны
        if (! empty($data['models']) && is_array($data['models'])) {
            foreach ($data['models'] as $modelType => $modelData) {
                // Внутри modelData: { "keys": { "title": "...", "description": "..." } }
                if (! empty($modelData['keys']) && is_array($modelData['keys'])) {
                    foreach ($modelData['keys'] as $keyName => $template) {
                        $seoKey = SeoKey::where('key', $keyName)->first();
                        if ($seoKey) {
                            $mt = SeoModelTemplate::firstOrNew([
                                'model_type'  => $modelType,
                                'seo_key_id'  => $seoKey->id,
                            ]);
                            $mt->template = $template;
                            $mt->save();
                        }
                    }
                }
            }
        }
    }
}
