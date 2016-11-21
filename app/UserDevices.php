<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserDevices extends Model
{
    protected $fillable = [
       'device_unique_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
