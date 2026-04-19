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
}
