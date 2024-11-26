<?php

namespace CoolmacJedi\SeoMetaManager\Models;

use Illuminate\Database\Eloquent\Model;

class SeoModel extends Model
{
    protected $table = 'seo_models';
    protected $fillable = [
        'model_type',
        'model_id',
    ];

    public function seoValues()
    {
        return $this->hasMany(SeoValue::class, 'seo_model_id');
    }
}
