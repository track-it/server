<?php

namespace Trackit\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = [
        'name',
    ];

    public static function boot()
    {
    	parent::boot();

    	static::created(function ($user) {
    		$user->refreshApiToken();
    	});
    }

    public function refreshApiToken()
    {
    	$this->api_token = str_random(128);
    	$this->save();
    }

    public function proposals()
    {
    	return $this->hasMany(Proposal::class);
    }

    public function role()
    {
    	return $this->belongsTo(Role::class);
    }

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
