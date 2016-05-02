<?php

namespace Trackit\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Contracts\Auth\Authenticatable;
use Hash;

class User extends Model implements Authenticatable
{
    use AuthenticatableTrait;

    protected $fillable = [
        'name',
    ];

    protected $hidden = [
        'password'
    ];

    public static function boot()
    {
    	parent::boot();

    	static::created(function ($user) {
    		$user->refreshApiToken();
    	});

        static::creating(function($user) {
            $user->password = Hash::make($user->password);
        });
    }

    public static function scopeByUsername($query, $username)
    {
        return $query->where('username', $username);
    }

    public function refreshApiToken()
    {
    	$this->api_token = str_random(128);
    	$this->save();
    }

    public function proposals()
    {
    	return $this->hasMany(Proposal::class, 'author_id');
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

    public function project()
    {
        return $this->hasMany(Project::class);
    }

    public function supervisor()
    {
        return $this->belongsToMany(Project::class, 'project_supervisor');
    }
}
