<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $fillable = ['name', 'slug', 'group', 'type', 'profile_id', 'min_value', 'max_value'];

    public function values()
    {
        return $this->hasMany(AttributeValue::class);
    }

}
