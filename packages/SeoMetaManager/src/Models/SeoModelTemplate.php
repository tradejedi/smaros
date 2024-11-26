<?php

namespace CoolmacJedi\SeoMetaManager\Models;

use Illuminate\Database\Eloquent\Model;

class SeoModelTemplate extends Model
{
    protected $table = 'seo_model_templates';
    protected $fillable = [
        'model_type',
        'seo_key_id',
        'template',
    ];

    public function seoKey()
    {
        return $this->belongsTo(SeoKey::class, 'seo_key_id');
    }
}
