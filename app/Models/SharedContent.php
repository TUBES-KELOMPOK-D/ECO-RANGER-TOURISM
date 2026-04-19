<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SharedContent extends Model
{
    use HasFactory;

    protected $table = 'shared_contents';
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'media_path',
        'type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
