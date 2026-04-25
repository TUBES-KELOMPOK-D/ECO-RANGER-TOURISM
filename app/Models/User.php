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
        $points = 0;

        // +10 points for each report
        $points += $this->reports()->count() * 10;

        // +50 points for each event participation
        $points += $this->eventParticipations()->count() * 50;

        // +5 points for each verified report (by the user who reported it)
        $points += $this->reports()
            ->where('status', 'diverifikasi')
            ->count() * 5;

        // +15 points for each forum post
        $points += $this->forumPosts()->count() * 15;

        // +20 points for each shared content
        $points += $this->sharedContents()->count() * 20;

        return $points;
    }

    /**
     * Calculate eco level based on points
     */
    public function calculateEcoLevel()
    {
        $points = $this->eco_points ?? 0;

        if ($points >= 1500) {
            return 'Eco-Hero';
        }

        if ($points >= 500) {
            return 'Eco-Ranger';
        }

        return 'Eco-Newbie';
    }

    /**
     * Get user rank in global leaderboard
     */
    public function getRankAttribute()
    {
        return self::where('eco_points', '>', $this->eco_points)
            ->count() + 1;
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
