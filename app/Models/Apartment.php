<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Apartment extends Model
{
    protected $guarded = [];
    protected $fillable = [
        'title', 'owner_id', 'monthly_rent', 'address', 'postal_code',
        'state', 'city', 'municipality', 'locality', 'nearby',
        'description', 'lat', 'lng',
        'available_from', 'is_furnished',
        'has_ac', 'has_tv', 'has_wifi', 'has_kitchen', 'has_parking',
        'has_laundry', 'has_heating', 'has_balcony',
        'pets_allowed', 'smoking_allowed',
        'max_people', 'status', 'rules', 'photos',
    ];

    protected $casts = [
        'photos' => 'array',
        'nearby' => 'array',
        'rules' => 'array',
        'available_from' => 'date',
        'is_furnished' => 'boolean',
        'has_ac' => 'boolean',
        'has_tv' => 'boolean',
        'has_wifi' => 'boolean',
        'has_kitchen' => 'boolean',
        'has_parking' => 'boolean',
        'has_laundry' => 'boolean',
        'has_heating' => 'boolean',
        'has_balcony' => 'boolean',
        'pets_allowed' => 'boolean',
        'smoking_allowed' => 'boolean',
    ];


    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function comments()
    {
        return $this->hasMany(ApartmentComment::class)->latest();
    }

    /** Usuarios que tienen este alojamiento en favoritos. */
    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    /** Precio por día (derivado de renta mensual / 30). */
    public function getDailyRateAttribute(): float
    {
        return round($this->monthly_rent / 30, 2);
    }
}
