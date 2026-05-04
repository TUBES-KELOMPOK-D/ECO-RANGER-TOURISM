<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image_path',
        'poin_required',
        'kategori'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_vouchers')
                    ->withPivot('status', 'redeemed_at');
    }
}
