<?php

namespace CoolmacJedi\SeoMetaManager\Models;

use Illuminate\Database\Eloquent\Model;

class SeoValue extends Model
{
    protected $table = 'seo_values';
    protected $fillable = [
        'seo_model_id',
        'seo_key_id',
        'value',
        'is_active',
    ];

    public function seoModel()
    {
        return $this->belongsTo(SeoModel::class);
    }

    public function seoKey()
    {
        return $this->belongsTo(SeoKey::class);
    }
}
