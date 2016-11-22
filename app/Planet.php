<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Planet extends Model
{
    protected $fillable = [
        'starsystem_id', 'distance_from_star', 'type'
    ];

    public function starsystem()
    {
        return $this->belongsTo(Starsystem::class);
    }
}
