<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReadingData extends Model
{
    public $timestamps = false;
    protected $table = 'reading_data';
    protected $fillable = [
        'id_termostat_features',
        'value',
        'created_date',
        'reading_date'
    ];

    public function termostat_features() : BelongsTo{
        return $this->BelongsTo(Feature::class,'id_termostat_features');
    }
}
