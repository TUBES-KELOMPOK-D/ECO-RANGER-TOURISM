<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';

    protected $fillable = [
        'name',
        'event_date',
        'location',
        'description',
        'organizer',
        'image_path',
    ];

    protected $casts = [
        'event_date' => 'date',
    ];

    /**
     * Peserta yang mengikuti event ini (pivot: participant_events).
     */
    public function participants()
    {
        return $this->belongsToMany(User::class, 'participant_events', 'event_id', 'user_id')
                    ->withPivot('created_at');
    }

    /**
     * Pesan chat grup untuk event ini.
     */
    public function messages()
    {
        return $this->hasMany(EventMessage::class);
    }

    /**
     * Cek apakah user tertentu sudah bergabung di event ini.
     */
    public function isJoinedBy(int $userId): bool
    {
        return $this->participants()->where('user_id', $userId)->exists();
    }
}
