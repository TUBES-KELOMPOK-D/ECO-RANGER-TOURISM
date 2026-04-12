<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Poin extends Model
{
    protected $table = 'poin';

    protected $fillable = [
        'user_id',
        'points',
        'source',
    ];

    protected function casts(): array
    {
        return [
            'points' => 'integer',
        ];
    }

    /**
     * Poin milik seorang user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
