<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    protected $table = 'profile_photos';

    protected $fillable = [
        'profile_id',
        'path'
    ];

    // Связь с профилем
    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }
}
