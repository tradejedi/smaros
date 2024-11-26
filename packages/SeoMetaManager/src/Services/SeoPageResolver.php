<?php

namespace CoolmacJedi\SeoMetaManager\Services;

use CoolmacJedi\SeoMetaManager\Models\SeoModel;

/**
 * Класс, отвечающий за "разрешение" (resolve) SEO для любой Eloquent-модели приложения.
 *
 * Пример использования в контроллере приложения:
 *
 *   public function show($id)
 *   {
 *       $post = Post::findOrFail($id);
 *       $seoTags = app(\Vendor\SeoMetaManager\Services\SeoPageResolver::class)->resolve($post);
 *
 *       return view('post.show', compact('post', 'seoTags'));
 *   }
 */
class SeoPageResolver
{
    /**
     * Экземпляр SeoMetaService, чтобы вызывать getMetaTagsFor().
     *
     * @var \CoolmacJedi\SeoMetaManager\Services\SeoMetaService
     */
    protected $seoMetaService;

    /**
     * Внедрение SeoMetaService через конструктор.
     *
     * @param  \CoolmacJedi\SeoMetaManager\Services\SeoMetaService  $seoMetaService
     */
    public function __construct(SeoMetaService $seoMetaService)
    {
        $this->seoMetaService = $seoMetaService;
    }

    /**
     * Основной метод: принимает модель приложения (Eloquent) и возвращает массив SEO-тегов.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return array  Ассоциативный массив вида ['title' => '...', 'description' => '...', ...]
     */
    public function resolve($model): array
    {
        // 1. Определяем класс и ID модели
        $modelType = get_class($model);      // например, "App\Models\Post"
        $modelId   = $model->getKey();       // ID модели (int, bigint)

        // 2. Находим или создаём SeoModel
        $seoModel = SeoModel::firstOrCreate([
            'model_type' => $modelType,
            'model_id'   => $modelId,
        ]);

        // 3. Вызываем SeoMetaService, чтобы собрать итоговые метатеги
        $seoTags = $this->seoMetaService->getMetaTagsFor($seoModel);

        // 4. Возвращаем массив вида ['title' => '...', 'description' => '...']
        return $seoTags;
    }
}
