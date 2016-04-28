<?php

namespace Trackit\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    const COMPLETED = 1;
    const NOT_COMPLETED = 2;

    const STATUSES = [
        self::COMPLETED,
        self::NOT_COMPLETED,
    ];

    protected $fillable = [
        'status',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}