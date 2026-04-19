<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kuis extends Model
{
    use HasFactory;

    protected $table = 'kuis';

    protected $fillable = [
        'title',
        'artikel_id',
        'questions',
    ];

    protected $casts = [
        'questions' => 'array',
    ];

    public function artikel()
    {
        return $this->belongsTo(Artikel::class, 'artikel_id');
    }
}
