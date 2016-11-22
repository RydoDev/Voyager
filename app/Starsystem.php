<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Starsystem extends Model
{
    protected $fillable = [
        'cluster_id', 'x', 'y'
    ];

    public function cluster()
    {
        return $this->belongsTo(Cluster::class);
    }

    public function star()
    {
        return $this->hasOne(Star::class);
    }

    public function planets()
    {
        return $this->hasMany(Planet::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
