<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Group extends Model
{
    public $timestamps  = false;

    protected $fillable = [
        'name',
        'id_user'
    ];

    public function devices(): BelongsToMany
    {
        return $this->belongsToMany(Device::class,'device_group','id_group','id_device');
    }

}
