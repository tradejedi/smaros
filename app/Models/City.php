<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    public function subCities()
    {
        return $this->hasMany(SubCities::class);
    }
    // Связь с анкетами (один ко многим)
    public function profiles()
    {
        return $this->hasMany(Profile::class);
    }

    // Связь с метро (один ко многим)
    public function metroStations()
    {
        return $this->hasMany(MetroStation::class);
    }

    // Связь с районами (один ко многим)
    public function districts()
    {
        return $this->hasMany(District::class);
    }
}
