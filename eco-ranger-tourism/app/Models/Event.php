<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';
    protected $fillable = [
        'name',
        'event_date',
        'location',
        'description',
        'image_path',
    ];

    protected $casts = [
        'event_date' => 'date',
    ];

    public function participants()
    {
        return $this->belongsToMany(User::class, 'participant_events');
    }
}
