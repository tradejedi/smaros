<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'city_id', 'slug'];

    // Связь с городом (многие ко одному)
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    // Связь с профилями (многие ко многим)
    public function profiles()
    {
        return $this->belongsToMany(Profile::class, 'district_profile');
    }
}
