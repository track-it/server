<?php

namespace Trackit\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
	protected $fillable = [
    	'course',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_teams');
    }

    public function project()
    {
        return $this->hasOne(Project::class);
    }
}
