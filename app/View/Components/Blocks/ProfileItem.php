<?php

namespace App\View\Components\Blocks;

use App\Models\Profile;
use App\Services\ProfileService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class ProfileItem extends Component
{
    public Profile $profile;
    protected ProfileService $profileService;
    public Collection|array $profileAttributes;

    public function __construct(Profile $profile, ProfileService $profileService)
    {
        $this->profile = $profile;
        $this->profileService = $profileService;
        $this->profileAttributes = $this->profileService->getAttributesGroupedByGroup($profile);
    }

    /**
     * Сформировать ссылку на просмотр профиля по маршруту profiles.show
     */
    public function profileLink(): string
    {
        return route('profiles.show', [
            // "profile" и "slug" - это ключи, соответствующие {profile}-{slug}
            // в маршруте /profiles/{profile}-{slug}.
            'profile' => $this->profile->id,
            'slug'    => $this->profile->slug,
        ]);
    }

    public function metroStationsLinks(): string
    {
        $metroStations = $this->profile->metroStations->pluck('name', 'id');

        return $metroStations->map(function ($name, $id) {
            $url = 'metro-stations/' . $id;
            return '<a href="' . e($url) . '" class="text-blue-500 hover:underline">' . e($name) . '</a>';
        })->join(', ');
    }

    /**
     * Получить список случайных атрибутов профиля (до 5 штук).
     *
     * @return Collection
     */
    public function randomAttributes(): Collection
    {
        // 1. Получаем коллекцию, сгруппированную по group,
        //    где каждая группа — это коллекция пар [slug => value]
        $grouped = $this->profileAttributes;

        // 2. "Раскрываем" (flatMap) структуру из
        //    ['group' => Collection([ 'slug' => 'value' ])]
        //    в список элементов [{ group, slug, value }]
        $flattened = $grouped->flatMap(function (Collection $slugValueCollection, string $groupName) {
            return $slugValueCollection->map(function ($value, $slug) use ($groupName) {
                return [
                    'group' => $groupName,
                    'slug'  => $slug,
                    'value' => $value,
                ];
            });
        });

        // 3. Фильтруем только элементы, у которых value === 'Yes'
        $filteredYes = $flattened->filter(function ($item) {
            return $item['value']['value'] === 'Yes';
        });

        // 4. Перемешиваем и берём случайные 5 элементов
        return $filteredYes->shuffle()->take(5);
    }

    /**
     * Возвращает массив атрибутов, где ключ — slug атрибута, а значение — параметры атрибута.
     *
     * @return array
     */
    public function attributesBySlug(): array
    {
        $attributes = [];

        foreach ($this->profile->attributeValues as $attributeValue) {
            $attribute = $attributeValue->attribute;

            if (!isset($attributes[$attribute->slug])) {
                $attributes[$attribute->slug] = [
                    'name' => $attribute->name,
                    'type' => $attribute->type,
                    'min_value' => $attribute->min_value,
                    'max_value' => $attribute->max_value,
                    'values' => [],
                ];
            }

            $attributes[$attribute->slug]['values'][] = $attributeValue->value;
        }

        return $attributes;
    }

    /**
     * Возвращает массив атрибутов с группой 'price', отсортированных по slug.
     *
     * @return array
     */
    public function priceAttributesSortedBySlug(): array
    {
        return $this->profile->attributeValues
            ->filter(function ($attributeValue) {
                return $attributeValue->attribute->group === 'price';
            })
            ->pluck('value', 'attribute.slug')
            ->toArray();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.blocks.profile-item', [
            'prices' => $this->priceAttributesSortedBySlug()
        ]);
    }
}
