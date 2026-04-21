<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marker extends Model
{
    protected $guarded = [];

    protected $casts = [
        'coordinates' => 'array',
        'radius' => 'float',
        'eco_rules' => 'array',
        'eco_health_score' => 'float',
    ];
}