<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Timer extends Model
{
    public $timestamps  = false;
    public $table = "timer";
    protected $fillable = [
        'name',
        'time',
        'status',
        'active',
        'id_user'
    ];

    public function devices() : BelongsToMany{
        return $this->belongsToMany(Device::class,"timer_devices","id_timer","id_device");
    }
    public function days() : BelongsToMany{
        return $this->belongsToMany(Day::class,"timer_days","id_timer","id_day");
    }
}
