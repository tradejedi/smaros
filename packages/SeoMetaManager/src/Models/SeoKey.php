<?php

namespace CoolmacJedi\SeoMetaManager\Models;

use Illuminate\Database\Eloquent\Model;

class SeoKey extends Model
{
    protected $table = 'seo_keys';
    protected $fillable = [
        'key',
        'global_template',
    ];
}
