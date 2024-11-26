<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContactTypes extends Model
{
    use HasFactory;

    protected $table = 'contact_types';
    protected $fillable = [
        'name',
        'slug'
    ];

    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class, 'type_id', 'id');
    }
}
