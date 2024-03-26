<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Department extends Model
{
    use HasFactory;
    protected $fillable = [
        'department',
        'description'
    ];
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($department) {
            if ($department->positions()->exists()) {
                return false;
            }
        });
    }
    public function positions(): HasMany
    {
        return $this->hasMany(Position::class, 'department_id');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
