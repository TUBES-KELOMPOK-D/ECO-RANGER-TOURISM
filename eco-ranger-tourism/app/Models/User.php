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
     * Calculate eco level based on points
     */
    public function calculateEcoLevel()
    {
        $points = $this->eco_points ?? 0;

        if ($points >= 2400) {
            return 'ECO-RANGER';
        } elseif ($points >= 2000) {
            return 'ECO-WARRIOR';
        } elseif ($points >= 1000) {
            return 'ECO-LEADER';
        } else {
            return 'ECO-MEMBER';
        }
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
