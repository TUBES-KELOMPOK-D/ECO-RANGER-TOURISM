<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marker extends Model
{
    protected $guarded = [];

    protected $casts = [
        'coordinates' => 'array',
        'radius' => 'float',
    ];
}