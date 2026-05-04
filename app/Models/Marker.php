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

    /**
     * Review/ulasan yang diberikan untuk lokasi ini.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}