<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    public $table = "categories";

    public function devices() : HasMany{
        return $this->hasMany(Device::class,'id_category');
    }
}
