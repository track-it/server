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

    public function assignTeam($team)
    {
        $this->team()->associate($team);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}