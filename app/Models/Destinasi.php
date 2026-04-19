<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Destinasi extends Model
{
    protected $table = 'destinasi';
    protected $fillable = [
        'name',
        'description',
        'location', 
        'latitude',
        'longitude',
        'status'
    ];
}
