<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'marker_id',
        'destinasi_id',
        'user_id',
        'review_text',
        'rating',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    /**
     * User yang menulis review ini.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Marker yang di-review.
     */
    public function marker()
    {
        return $this->belongsTo(Marker::class);
    }
}
