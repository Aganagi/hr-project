<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    public function departments(): HasMany
    {
        return $this->hasMany(Department::class, 'user_id');
    }
    public function positions(): HasMany
    {
        return $this->hasMany(Position::class, 'user_id');
    }
    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class, 'user_id');
    }
    public function workschedules(): HasMany
    {
        return $this->hasMany(WorkSchedule::class, 'user_id');
    }
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'user_id');
    }
    public function leaveAndAbsences(): HasMany
    {
        return $this->hasMany(LeaveAndAbsence::class, 'user_id');
    }
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($user) {
            $user->departments()->delete();
            $user->positions()->delete();
            $user->employees()->delete();
            $user->workschedules()->delete();
            $user->projects()->delete();
            $user->leaveAndAbsences()->delete();
        });
    }
}
