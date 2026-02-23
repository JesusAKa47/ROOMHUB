<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApartmentPhoto extends Model
{
    protected $guarded = [];

    public function apartment()
    {
        return $this->belongsTo(Apartment::class);
    }
}
