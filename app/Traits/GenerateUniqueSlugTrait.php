<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait GenerateUniqueSlugTrait
{
    public static function generateUniqueSlug(Model $model): void
    {
        $model->slug = ($model->slug !== null) ? $model->slug : Str::slug($model->name);
    }

    public static function checkIfSlugExists(Model $model): bool
    {
        return $model::where("slug", $model->slug)->exists();
    }
}
