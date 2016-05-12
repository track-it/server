<?php

namespace Trackit\Models;

use Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;

class User extends Model implements Authenticatable
{
    use AuthenticatableTrait;

    /**
     * @var array
     */
    protected $fillable = [
        'username',
        'password',
        'email',
        'displayname',
        'api_token',
        'confirmed',
        'role_id',
    ];

    /**
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Boots the Eloquent model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            $user->refreshApiToken();
        });

        static::creating(function ($user) {
            $user->password = Hash::make($user->password);
        });
    }

    /**
     * Defines a scope to query a user by its username.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $username
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function scopeByUsername($query, $username)
    {
        return $query->where('username', $username);
    }

    /**
     * Refresh the user's api token.
     *
     * @return void
     */
    public function refreshApiToken()
    {
        $this->api_token = str_random(128);
        $this->save();
    }

    /**
     * Get the relationship between the user and its proposals.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function proposals()
    {
        return $this->hasMany(Proposal::class, 'author_id');
    }

    /**
     * Get the relationship between the user and its role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Join the given team.
     *
     * @param  \Trackit\Models\Team|int  $team
     * @return void
     */
    public function joinTeam($team)
    {
        $this->teams()->attach($team);
    }

    /**
     * Leave the given team.
     *
     * @param  \Trackit\Models\Team|int  $team
     * @return void
     */
    public function leaveTeam($team)
    {
        $this->teams()->detach($team);
        if ($team->fresh()->users->count() == 0) {
            $team->delete();
        }
    }

    /**
     * Check if the user can perform the given action.
     *
     * @param  string  $action
     * @return boolean
     */
    public function can($action)
    {
        return $this->role->can($action);
    }

    /**
     * Get the relationship between the user and its teams.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function teams()
    {
        return $this->belongsToMany(Team::class, 'user_teams');
    }

    /**
     * Get the relationship betweent the user and the projects it is participating in
     */
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_user')->withPivot('project_role_id');
    }

    /**
     * Override newPivot function to provide custom ProjectUser pivot model
     */
    public function newPivot(Model $parent, array $attributes, $table, $exists)
    {
        if ($parent instanceof Project) {
            //dd(compact('attributes', ''))
            return new ProjectUser($parent, $attributes, $table, $exists);
        }
        return parent::newPivot($parent, $attributes, $table, $exists);
    }
}
