<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $table = 'roles';
    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    public function users(): hasMany
    {
        return $this->hasMany(User::class);
    }
}
