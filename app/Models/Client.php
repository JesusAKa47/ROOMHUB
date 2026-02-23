<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $guarded = [];
    protected $casts = [
        'birthdate' => 'date',
        'is_verified' => 'boolean',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'client_id');
    }
}
