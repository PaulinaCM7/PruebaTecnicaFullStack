<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    protected $fillable = [
        'media_id', 'date',
    ];

    public function media()
    {
        return $this->belongsTo(Media::class);
    }
}
