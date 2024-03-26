<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'position_id',
        'fname',
        'lname',
        'email',
        'phone',
        'hireDate'
    ];

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class, 'position_id');
    }

    public function workShedules(): HasMany
    {
        return $this->hasMany(WorkSchedule::class, 'employee_id');
    }
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'employee_id');
    }
    public function getFullNameAttribute()
    {
        return $this->attributes['fname'] . ' ' . $this->attributes['lname'];
    }
    // public function leaveandabsence(): HasMany
    // {
    //     return $this->hasMany(LeaveAndAbsence::class, 'employee_id');
    // }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
