<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PointRule extends Model
{
    //
    protected $fillable = [
        'action_key',
        'action_name',
        'points_reward',
        'icon',
        'description',
    ];
}
