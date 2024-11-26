<?php

namespace App\Models;

use Dom\Attr;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'user_id', 'description', 'slug', 'city_id'];

    // Связь с пользователем (один ко многим)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Связь с городом (один ко многим)
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    // Связь с метро (многие ко многим)
    public function metroStations()
    {
        return $this->belongsToMany(MetroStation::class, 'metro_profile');
    }

    // Связь с районами (многие ко многим)
    public function districts()
    {
        return $this->belongsToMany(District::class, 'district_profile');
    }

    // Связь с атрибутами профиля
    public function attributes()
    {
        return $this->belongsToMany(
            Attribute::class,
            'profile_attribute_values',
            'profile_id',
            'attribute_id'
        )->withPivot(['value_decimal', 'value_boolean', 'attribute_value_id']);
    }

    // Получить значения атрибутов для профиля
    public function attributeValues()
    {
        return $this->hasMany(ProfileAttributeValue::class);
    }

    // Связь с контактами (один ко многим)
    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    // Связь с комментариями (один ко многим)
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }
}
