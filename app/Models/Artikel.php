<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artikel extends Model
{
    use HasFactory;

    protected $table = 'artikels';

    protected $fillable = [
        'title',
        'content',
        'image_path',
        'points',
        'duration',
    ];

    protected $casts = [
        'points' => 'integer',
    ];

    public function quiz()
    {
        return $this->hasOne(Kuis::class, 'artikel_id');
    }

    public function progresses()
    {
        return $this->hasMany(UserProgress::class, 'artikel_id');
    }
}
