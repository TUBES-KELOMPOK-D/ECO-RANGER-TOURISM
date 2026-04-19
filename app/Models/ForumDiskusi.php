<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ForumDiskusi extends Model
{
    use HasFactory;

    protected $table = 'forum_diskusis';
    protected $fillable = [
        'user_id',
        'topic',
        'message',
    ];

    public $timestamps = false;
    public const CREATED_AT = 'created_at';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
