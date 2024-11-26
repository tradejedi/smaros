<?php

namespace CoolmacJedi\SeoMetaManager\Services;

use Illuminate\Support\Facades\Cache;
use CoolmacJedi\SeoMetaManager\Models\SeoKey;
use CoolmacJedi\SeoMetaManager\Models\SeoModel;
use CoolmacJedi\SeoMetaManager\Models\SeoModelTemplate;
use CoolmacJedi\SeoMetaManager\Models\SeoValue;

class SeoMetaService
{
    /**
     * Получить мета-теги (все) для указанного seoModel (привязанного к записи).
     *
     * Алгоритм по каждому мета-ключу:
     *   1) Проверить seo_values (конкретная запись)
     *   2) Если нет, проверить seo_model_templates (для данного model_type)
     *   3) Если нет, взять global_template из seo_keys
     */
    public function getMetaTagsFor(SeoModel $seoModel): array
    {
        $cacheKey = 'seo_meta_'.$seoModel->id;
        $ttl = 3600; // или config('seometa.ttl')

        return Cache::remember($cacheKey, $ttl, function () use ($seoModel) {
            $modelType = $seoModel->model_type;  // "App\Models\Post" и т.п.

            // Сбор всех ключей
            $allKeys = SeoKey::all()->keyBy('id');

            // Значения из БД (п.1)
            $values = SeoValue::where('seo_model_id', $seoModel->id)
                ->where('is_active', true)
                ->get()
                ->keyBy('seo_key_id');

            // Модельные шаблоны (п.2)
            $modelTemplates = SeoModelTemplate::where('model_type', $modelType)
                ->get()
                ->keyBy('seo_key_id');

            $tags = [];

            foreach ($allKeys as $keyId => $seoKey) {
                $final = null;

                // 1) Конкретное значение в seo_values?
                if ($values->has($keyId)) {
                    $final = $values[$keyId]->value;
                }

                // 2) Если пусто, смотрим в seo_model_templates (шаблон для модели)
                if (!$final) {
                    if ($modelTemplates->has($keyId)) {
                        $template = $modelTemplates[$keyId]->template;
                        $final = $this->replacePlaceholders($template, $seoModel);
                    }
                }

                // 3) Если всё ещё пусто, берём global_template из seo_keys
                if (!$final && $seoKey->global_template) {
                    $final = $this->replacePlaceholders($seoKey->global_template, $seoModel);
                }

                // Если что-то получилось, запишем
                if ($final) {
                    $tags[$seoKey->key] = $final;
                }
            }

            return $tags;
        });
    }

    /**
     * Простая замена плейсхолдеров.
     * Можно расширить в зависимости от модели.
     */
    protected function replacePlaceholders(?string $template, SeoModel $seoModel)
    {
        if (!$template) {
            return null;
        }

        // Например, {{ siteName }} => config('app.name')
        $template = str_replace('{{ siteName }}', config('app.name', 'MySite'), $template);

        // Можно добавить логику, которая достаёт реальную запись $seoModel->model_type::find($seoModel->model_id)
        // и подставляет её поля

        return $template;
    }

    /**
     * Сохранение (или обновление) значений для конкретной записи.
     */
    public function updateSeoValues(SeoModel $seoModel, array $data)
    {
        foreach ($data as $key => $value) {
            $seoKey = SeoKey::where('key', $key)->first();
            if (!$seoKey) {
                continue;
            }

            $seoValue = SeoValue::firstOrNew([
                'seo_model_id' => $seoModel->id,
                'seo_key_id'   => $seoKey->id,
            ]);
            $seoValue->value     = $value;
            $seoValue->is_active = (bool)$value;
            $seoValue->save();
        }

        // Очистка кеша
        Cache::forget('seo_meta_'.$seoModel->id);
    }
}
