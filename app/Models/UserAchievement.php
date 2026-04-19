<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserAchievement extends Model
{
    use HasFactory;

    protected $table = 'user_achievements';

    protected $fillable = [
        'user_id',
        'achievement_key',
        'achievement_name',
        'description',
        'icon',
        'target',
        'current',
        'is_completed',
        'completed_at',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get progress percentage
     */
    public function getProgressPercentageAttribute()
    {
        return $this->target > 0 ? round(($this->current / $this->target) * 100) : 0;
    }

    /**
     * Check if achievement is completed
     */
    public function isCompleted()
    {
        return $this->current >= $this->target;
    }
}
