<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $fillable = [
        'name', 'location', 'type', 'image', 'price_per_day',
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function availabilities()
    {
        return $this->hasMany(Availability::class);
    }
}
