<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventMessage extends Model
{
    use HasFactory;

    protected $table = 'event_messages';

    protected $fillable = [
        'event_id',
        'user_id',
        'message',
        'image_path',
    ];

    /**
     * Event yang memiliki pesan ini.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * User yang mengirim pesan ini.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Reaksi pada pesan ini.
     */
    public function reactions()
    {
        return $this->hasMany(EventMessageReaction::class, 'event_message_id');
    }
}
