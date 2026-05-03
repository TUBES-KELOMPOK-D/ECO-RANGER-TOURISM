<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'icon',
        'description',
        'category',
        'target',
        'target_column',
        'target_condition',
        'points_reward',
        'level'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_badges')
            ->withPivot('achieved_at', 'progress_at_achievement')
            ->withTimestamps();
    }
}
