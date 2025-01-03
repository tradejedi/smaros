<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfileAttributeValue extends Model
{
    protected $fillable = ['profile_id', 'attribute_id', 'attribute_value_id', 'value_decimal', 'value_boolean'];

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    public function attributeValue()
    {
        return $this->belongsTo(AttributeValue::class, 'attribute_value_id')->with('attribute');
    }

    public function getValueAttribute()
    {
        $type = $this->attribute->type;

        switch ($type) {
            case 'list':
                return $this->attributeValue ? $this->attributeValue->value : null;
            case 'range':
                return $this->value_decimal;
            case 'boolean':
                if($this->attribute->group == 'price') {
                    return $this->value_boolean;
                }
                else {
                    return $this->value_boolean ? 'Yes' : 'No';
                }
            default:
                return null;
        }
    }
}
