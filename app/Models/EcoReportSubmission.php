<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class EcoReportSubmission extends Model
{
    use HasFactory;

    protected $table = 'eco_report_submissions';

    protected $fillable = [
        'user_id',
        'title',
        'category',
        'description',
        'latitude',
        'longitude',
        'location',
        'photo_path',
        'status',
        'report_date',
    ];

    protected $casts = [
        'report_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
