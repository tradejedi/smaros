<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contact extends Model
{
    use HasFactory;
    protected $fillable = ['profile_id', 'type_id', 'value'];

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    public function contactType(): BelongsTo
    {
        return $this->belongsTo(ContactTypes::class, 'type_id', 'id');
    }
}
