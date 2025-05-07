<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Termostat extends Model
{
    protected $table = "termostat";
    public $timestamps  = false;
    protected $fillable = [
        'name',
        'id_user'
    ];

    public function devices() : BelongsToMany{
        return $this->belongsToMany(Device::class,"termostat_devices","id_termostat","id_device")
            ->withPivot('desired_temperature','maintain_temperature');
    }
    public function features() : BelongsToMany{
        return $this->belongsToMany(Feature::class,"termostat_features","id_termostat","id_feature");
    }
    public function data() : BelongsToMany {
        return $this->belongsToMany(Feature::class, 'termostat_features', 'id_termostat', 'id_feature') // Veza između Termostat i Feature
        ->join('reading_data', 'reading_data.id_termostat_features', '=', 'termostat_features.id') // Veza sa reading_data
        ->withPivot('reading_data.value', 'reading_data.created_date', 'reading_data.reading_date')
            ->as('reading')
            ->orderByDesc('reading_data.created_date');
    }
}
