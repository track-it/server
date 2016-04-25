<?php

namespace Trackit\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = [
        'name',
    ];

    public function joinTeam($team)
    {
    	$this->teams()->attach($team);
    }

    public function leaveTeam($team)
    {
        $this->teams()->detach($team);
        if($team->fresh()->users->count() == 0){
            $team->delete();
        }
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'user_teams');
    }
}
