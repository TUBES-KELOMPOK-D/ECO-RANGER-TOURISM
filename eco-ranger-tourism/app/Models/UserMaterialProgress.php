<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserMaterialProgress extends Model
{
    protected $fillable = [
        'user_id', 'material_id',
        'is_completed', 'completed_at', 'earned_points',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'is_completed'  => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
