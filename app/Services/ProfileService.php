<?php

namespace App\Services;

use App\Models\Profile;
use App\Models\ProfileAttributeValue;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class ProfileService
{
    /**
     * Базовая заготовка для запроса: подгружаем связи и выбираем нужные поля.
     * Используется внутри методов пагинации и load-more, чтобы не дублировать код.
     */
    public function baseQuery(): Builder
    {

        return Profile::with([
            // Если нужно — подгружаем пользователя, выбрав нужные поля
            'user:id,name',

            // Город (аналогично)
            'city:id,name',

            // Метро (многие ко многим)
            'metroStations:id,name,slug',

            // Районы (многие ко многим)
            'districts:id,name',

            // Значения атрибутов (каждое хранит связь attribute и attributeValue)
            'attributeValues' => function ($query) {
                $query->with([
                    'attribute:id,name,slug,type,group',
                    'attributeValue:id,value',
                ]);
            },

            // Контакты (каждый контакт может иметь связь contactType, если нужно)
            'contacts:id,profile_id,type_id,value',
            'contacts.contactType:id,name,slug',

            // Комментарии (при необходимости)
            'comments:id,profile_id,user_id,content,created_at',

            // Фотографии (при необходимости)
            'photos:id,profile_id,path',
        ]);
    }

    /**
     * 1) Постраничная навигация (Pagination).
     * Возвращает LengthAwarePaginator, который в Blade можно выводить через {{ $profiles->links() }}.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getLatestProfilesWithPagination(int $perPage = 10): LengthAwarePaginator
    {
        return $this->baseQuery()->latest()->paginate($perPage);
    }

    /**
     * 2) Загрузка дополнительных анкет ("Load More").
     * Реализуем offset-based подход:
     * - $page = 1, 2, 3...
     * - $perPage = кол-во записей на "порцию"
     *
     * @param int $page    - Текущая страница для логики load more
     * @param int $perPage - Сколько записей брать за раз
     * @return Collection
     */
    public function getLatestProfilesLoadMore(int $page = 1, int $perPage = 10): Collection
    {
        $offset = ($page - 1) * $perPage;

        return $this->baseQuery()
            ->latest()
            ->skip($offset)
            ->take($perPage)
            ->get();
    }

    /**
     * Получить один профиль с подробными данными.
     *
     * @param int $profileId
     * @return Profile
     *
     */
    public function getProfileWithDetails(int $profileId): Profile
    {
        return $this->baseQuery()->findOrFail($profileId);
    }

    /**
     * Создать новый профиль с данными и связями.
     *
     * @param array $profileData
     * @param array $contactsData
     * @param array $attributesData
     * @return Profile
     */
    public function createProfile(array $profileData, array $contactsData = [], array $attributesData = []): Profile
    {
        return DB::transaction(function () use ($profileData, $contactsData, $attributesData) {
            // Создаём профиль
            $profile = Profile::create($profileData);

            // Создаём контакты, если есть
            if (!empty($contactsData)) {
                $profile->contacts()->createMany($contactsData);
            }

            // Привязываем атрибуты, если есть
            if (!empty($attributesData)) {
                $profile->attributeValues()->createMany($attributesData);
            }

            // Привязываем станции метро, если есть
            if (!empty($profileData['metro_station_ids'])) {
                $profile->metroStations()->sync($profileData['metro_station_ids']);
            }

            // Привязываем районы, если есть
            if (!empty($profileData['district_ids'])) {
                $profile->districts()->sync($profileData['district_ids']);
            }

            return $profile;
        });
    }

    /**
     * Обновить существующий профиль с данными и связями.
     *
     * @param Profile $profile
     * @param array $data
     * @param array $contactsData
     * @param array $attributesData
     * @return bool
     */
    public function updateProfile(Profile $profile, array $data, array $contactsData = [], array $attributesData = []): bool
    {
        return DB::transaction(function () use ($profile, $data, $contactsData, $attributesData) {
            // Обновляем профиль
            $updated = $profile->update($data);

            // Обновляем контакты, если есть
            if (!empty($contactsData)) {
                // Например, можно сначала удалить старые, потом создать новые
                $profile->contacts()->delete();
                $profile->contacts()->createMany($contactsData);
            }

            // Обновляем атрибуты, если есть
            if (!empty($attributesData)) {
                // Аналогично: удаляем старые и создаём новые
                $profile->attributeValues()->delete();
                $profile->attributeValues()->createMany($attributesData);
            }

            // Обновляем станции метро, если есть
            if (!empty($data['metro_station_ids'])) {
                $profile->metroStations()->sync($data['metro_station_ids']);
            }

            // Обновляем районы, если есть
            if (!empty($data['district_ids'])) {
                $profile->districts()->sync($data['district_ids']);
            }

            return $updated;
        });
    }

    /**
     * Удалить профиль и связанные данные.
     *
     * @param Profile $profile
     * @return bool|null
     */
    public function deleteProfile(Profile $profile): ?bool
    {
        return DB::transaction(function () use ($profile) {
            // Удаляем связанные контакты, атрибуты, фотографии и комментарии
            $profile->contacts()->delete();
            $profile->attributeValues()->delete();
            $profile->comments()->delete();
            $profile->photos()->delete();

            // Отсоединяем многие-ко-многим связи
            $profile->metroStations()->detach();
            $profile->districts()->detach();

            // Удаляем сам профиль
            return $profile->delete();
        });
    }

    /**
     * Получить атрибуты профиля в удобном формате.
     *
     * @param Profile $profile
     * @return array
     */
    public function getProfileAttributes(Profile $profile): array
    {
        return $profile->attributeValues
            ->map(function ($attributeValue) {
                return [
                    'name' => $attributeValue->attribute->name,
                    'value' => $attributeValue->value,
                    'group' => $attributeValue->attribute->group,
                    'type' => $attributeValue->attribute->type,
                ];
            })
            ->groupBy('group')
            ->toArray();
    }

    /**
     * Получить атрибуты профиля, исключая группы 'variants' и 'price'.
     *
     * @param Profile $profile
     * @return array
     */
    public function getBooleanProfileAttributes(Profile $profile): array
    {
        return $profile->attributeValues
            ->filter(function ($attributeValue) {
                return !in_array($attributeValue->attribute->group, ['variants', 'price']);
            })
            ->map(function ($attributeValue) {
                return [
                    'name' => $attributeValue->attribute->name,
                    'value' => $attributeValue->value,
                    'group' => $attributeValue->attribute->group,
                    'type' => $attributeValue->attribute->type,
                ];
            })
            ->groupBy('group')
            ->toArray();
    }


    /**
     * Получить цену профиля (пример).
     *
     * @param Profile $profile
     * @return array
     */
    public function getPrices(Profile $profile): array
    {
        return $profile->attributeValues
            ->filter(function ($attributeValue) {
                return $attributeValue->attribute->group === 'price'; // Фильтруем только группу 'price'
            })
            ->map(function ($attributeValue) {
                return [
                    'id' => $attributeValue->id,
                    'label' => $attributeValue->attribute->name, // Название атрибута
                    'amount' => $attributeValue->value, // Используем виртуальный атрибут
                ];
            })
            ->toArray();
    }

    /**
     * Получить станции метро профиля из загруженных данных.
     *
     * @param Profile $profile
     * @return array
     */
    public function getMetroStations(Profile $profile): array
    {
        return $profile->metroStations->map(function ($station) {
            return [
                'id' => $station->id,
                'name' => $station->name,
                'slug' => $station->slug,
            ];
        })->toArray();
    }

    /**
     * Получить комментарии профиля.
     *
     * @param Profile $profile
     * @return Collection
     */
    public function getComments(Profile $profile): Collection
    {
        return $profile->comments()->with('user:id,name')->latest()->get();
    }

    /**
     * Получить похожие профили по району.
     *
     * @param Profile $profile
     * @param int $limit
     * @return Collection
     */
    public function getSimilarProfiles(Profile $profile, int $limit = 2): Collection
    {
        return Profile::where('id', '!=', $profile->id)
            ->with(['user:id,name', 'photos:id,profile_id,path'])
            ->latest()
            ->take($limit)
            ->get();
    }

    /**
     * Получить список профилей по категории услуги (label).
     *
     * @param string $slug
     * @param int $limit
     * @return Collection
     */
    public function getProfilesByService(string $slug, int $limit = 10): Collection
    {
        return Profile::whereHas('attributeValues.attribute', function ($query) use ($slug) {
            $query->where('slug', $slug);
        })
            ->with(['user:id,name', 'photos:id,profile_id,path'])
            ->latest()
            ->take($limit)
            ->get();
    }

    /**
     * Получить профили по станции метро.
     *
     * @param string $slug
     * @param int $limit
     * @return Collection
     */
    public function getProfilesByMetro(string $slug, int $limit = 10): Collection
    {
        return Profile::whereHas('metroStations', function ($query) use ($slug) {
            $query->where('slug', $slug);
        })
            ->with(['user:id,name', 'photos:id,profile_id,path'])
            ->latest()
            ->take($limit)
            ->get();
    }

    /**
     * Получить профили по городу.
     *
     * @param string $slug
     * @param int $limit
     * @return Collection
     */
    public function getProfilesByCity(string $slug, int $limit = 10): Collection
    {
        return Profile::whereHas('city', function ($query) use ($slug) {
            $query->where('slug', $slug);
        })
            ->with(['user:id,name', 'photos:id,profile_id,path'])
            ->latest()
            ->take($limit)
            ->get();
    }

    /**
     * Получить профили по району.
     *
     * @param string $slug
     * @param int $limit
     * @return Collection
     */
    public function getProfilesByDistrict(string $slug, int $limit = 10): Collection
    {
        return Profile::whereHas('districts', function ($query) use ($slug) {
            $query->where('slug', $slug);
        })
            ->with(['user:id,name', 'photos:id,profile_id,path'])
            ->latest()
            ->take($limit)
            ->get();
    }

    /**
     * Поиск профилей по расширенным критериям.
     *
     * @param array $filters
     * @param int $limit
     * @return Collection
     */
    public function advancedSearch(array $filters, int $limit = 20): Collection
    {
        $query = Profile::query();

        // Фильтрация по городу
        if (!empty($filters['city'])) {
            $query->whereHas('city', function ($q) use ($filters) {
                $q->where('slug', $filters['city']);
            });
        }

        // Фильтрация по метро
        if (!empty($filters['metro'])) {
            $query->whereHas('metroStations', function ($q) use ($filters) {
                $q->where('slug', $filters['metro']);
            });
        }

        // Фильтрация по услугам
        if (!empty($filters['services'])) {
            $query->whereHas('attributeValues.attribute', function ($q) use ($filters) {
                $q->whereIn('slug', $filters['services']);
            });
        }

        // Дополнительные фильтры...

        return $query->with(['user:id,name', 'photos:id,profile_id,path'])
            ->latest()
            ->take($limit)
            ->get();
    }

    /**
     * Возвращает структуру атрибутов, сгруппированных по group.
     *
     * @return array
     */

    public function getAttributesGroupedByGroup(Profile $profile): \Illuminate\Support\Collection
    {
        // Получаем все профильные значения атрибутов для конкретного профиля,
        // включая связанные Attribute и AttributeValue.
        $profileAttributeValues = ProfileAttributeValue::with(['attribute', 'attributeValue'])
            ->where('profile_id', $profile->id)
            ->get();

        // Группируем по полю "group" из Attribute и приводим к массиву,
        // формируя внутри каждой группы массив вида [ 'slug' => 'value' ].
        return $profileAttributeValues
            ->groupBy(fn ($pav) => $pav->attribute->group)
            ->map(function ($groupedValues) {
                // Для каждой группы формируем key-value,
                // где ключ — slug, а значение — value (учитывая логику getValueAttribute()).
                return $groupedValues->mapWithKeys(function ($pav) {
                    return [
                        $pav->attribute->slug => [
                            'value' => $pav->value,
                            'name' => $pav->attribute->name
                        ]
                    ];
                });
            });
    }

    /**
     * Вернёт массив (или коллекцию) атрибутов профиля.
     */
    public function getAttributesForProfile(Profile $profile): array
    {
        return $profile->attributeValues
            ->map(function ($attributeValue) {
                return [
                    'name' => $attributeValue->attribute->name,
                    'value' => $attributeValue->value,
                ];
            })
            ->toArray();
    }

    /**
     * Пример другой логики (фильтрация, сортировка и т.п.)
     */
    public function getRandomAttributes(Profile $profile, int $count = 5): array
    {
        return $profile->attributeValues
            ->shuffle()
            ->take($count)
            ->map(function ($attributeValue) {
                return [
                    'name' => $attributeValue->attribute->name,
                    'value' => $attributeValue->value,
                ];
            })
            ->toArray();
    }
}
