<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Star extends Model
{
    protected $fillable = [
        'starsystem_id', 'name', 'type'
    ];

    public function starsystem()
    {
        return $this->belongsTo(Starsystem::class);
    }
}
