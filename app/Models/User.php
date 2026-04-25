<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $table = "users";
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'eco_points',
        'eco_level',
        'photo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'eco_points' => 'integer',
        ];
    }

    /**
     * Event yang diikuti oleh user ini.
     */
    public function participatedEvents()
    {
        return $this->belongsToMany(Event::class, 'participant_events', 'user_id', 'event_id');
    }
    /**
     * Get user's achievements
     */
    public function achievements()
    {
        return $this->hasMany(UserAchievement::class);
    }

    /**
     * Get user's actions (community participation)
     */
    public function actions()
    {
        return $this->hasMany(Action::class);
    }

    /**
     * Get user's reports
     */
    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    /**
     * Get user's eco issue reports
     */
    public function ecoReports()
    {
        return $this->hasMany(EcoReportSubmission::class);
    }

    /**
     * Get user's forum posts
     */
    public function forumPosts()
    {
        return $this->hasMany(ForumDiskusi::class);
    }

    /**
     * Get user's shared content
     */
    public function sharedContents()
    {
        return $this->hasMany(SharedContent::class);
    }

    /**
     * Get user's event participations
     */
    public function eventParticipations()
    {
        return $this->belongsToMany(Event::class, 'participant_events');
    }

    /**
     * Get user's academy progress records.
     */
    public function academyProgress()
    {
        return $this->hasMany(UserProgress::class);
    }

    /**
     * Calculate total points from all activities (REAL-TIME from database)
     */
    public function getTotalPointsAttribute()
    {
        return app(\App\Services\LeaderboardService::class)->calculateUserPoints($this);
    }

    /**
     * Calculate eco level based on points
     */
    public function calculateEcoLevel()
    {
        return app(\App\Services\LeaderboardService::class)->getLevel($this->total_points);
    }

    /**
     * Get user rank in global leaderboard
     */
    public function getRankAttribute()
    {
        $rank = app(\App\Services\LeaderboardService::class)->getUserRank($this);
        return $rank ?? 1;
    }

    /**
     * Add eco points
     */
    public function addEcoPoints($points, $description = null)
    {
        $this->eco_points = ($this->eco_points ?? 0) + $points;
        $this->eco_level = $this->calculateEcoLevel();
        $this->save();

        return $this;
    }

    /**
     * Get user's initials
     */
    public function getInitialsAttribute()
    {
        $names = explode(' ', $this->name);
        $initials = '';
        foreach ($names as $name) {
            $initials .= strtoupper(substr($name, 0, 1));
        }
        return substr($initials, 0, 2);
    }
}
