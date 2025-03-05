<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
        'updated_date'
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
}
