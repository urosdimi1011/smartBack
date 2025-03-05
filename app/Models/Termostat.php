<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Termostat extends Model
{
    public $timestamps  = false;
    protected $fillable = [
        'name'
    ];

    public function devices() : BelongsToMany{
        return $this->belongsToMany(Device::class,"termostat_device","id_termostat","id_device");
    }
    public function features() : BelongsToMany{
        return $this->belongsToMany(Feature::class,"termostat_feature","id_termostat","id_feature");
    }
}
