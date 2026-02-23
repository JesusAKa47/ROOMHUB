<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    protected $guarded = [];
    protected $casts = [
        'since' => 'date',
        'is_active' => 'boolean',
    ];

    public function apartments()
    {
        return $this->hasMany(Apartment::class);
    }

    public function user()
    {
        return $this->hasOne(User::class, 'owner_id');
    }
}
