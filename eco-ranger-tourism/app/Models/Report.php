<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $table = 'eco_reports';

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'photo_path',
        'location',
        'category',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
