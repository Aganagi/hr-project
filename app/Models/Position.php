<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Position extends Model
{
    use HasFactory;
    protected $fillable = [
        'department_id',
        'position',
        'description',
        'salary'
    ];
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    // public function employees(): HasMany
    // {
    //     return $this->hasMany(Employee::class, 'position_id');
    // }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($position) {
            if ($position->employees()->exists()) {
                return false;
            }
        });
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
