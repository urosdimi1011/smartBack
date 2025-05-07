<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\belongsTo;

class Device extends Model
{
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';

    protected $fillable = [
        'id_category',
        'name',
        'status',
        'id_user',
        'pin',
        'board',
        'updated_date',
        'last_ip_address',
        'hasBrightness',
        'brightness'
    ];

    protected $hidden = [
        'password'
    ];

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'device_group', 'id_device', 'id_group');
    }
    public function category(): HasOne
    {
        return $this->HasOne(Category::class,'id','id_category');
    }
    public function timer() : BelongsToMany{
        return $this->belongsToMany(Timer::class,"timer_devices","id_device","id_timer");
    }
    public function termostat() : belongsToMany{
        return $this->belongsToMany(Termostat::class, 'termostat_devices', 'id_device', 'id_termostat')
            ->withPivot('desired_temperature', 'last_temperature','maintain_temperature')->as('info');
    }
}
