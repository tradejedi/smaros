<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['profile_id', 'user_id', 'content'];

    // Связь с анкетой (многие ко одному)
    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    // Связь с пользователем (многие ко одному)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
