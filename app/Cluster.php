<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cluster extends Model
{
    protected $fillable = [
        'name', 'x', 'y', 'density'
    ];

    public function starsystems()
    {
        return $this->hasMany(Starsystem::class);
    }
}
