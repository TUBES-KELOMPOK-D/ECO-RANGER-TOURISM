<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventMessageReaction extends Model
{
    use HasFactory;

    protected $table = 'event_message_reactions';

    protected $fillable = [
        'event_message_id',
        'user_id',
        'reaction',
    ];

    /**
     * Pesan yang diberikan reaksi.
     */
    public function message()
    {
        return $this->belongsTo(EventMessage::class, 'event_message_id');
    }

    /**
     * User yang memberikan reaksi.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
