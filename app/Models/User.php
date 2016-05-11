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
        'api_token',
        'role_id',
    ];

    /**
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * @var array
     */
    protected $appends = [
        'projects',
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
     * Add an attribute mutator to the user model listing all
     * of its projects.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getProjectsAttribute()
    {
        $projects = $this->manyThroughMany(Project::class, ProjectUser::class, 'user_id', 'id', 'project_id');
        $projects->select('projects.*', 'project_roles.name AS my_role');
        $projects->join('project_roles', 'project_roles.id', '=', 'project_users.project_role_id');

        return $projects->get();
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
     * Get the relationship between the user and its representation
     * of models related to any projects.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function projectUsers()
    {
        return $this->hasMany(ProjectUser::class)->with(['project', 'projectRole']);
    }

    /**
     * @blame albert@kaaman.nu
     */
    public function manyThroughMany($related, $through, $firstKey, $secondKey, $pivotKey)
    {
        $model = new $related;
        $table = $model->getTable();
        $throughModel = new $through;
        $pivot = $throughModel->getTable();

        return $model
            ->join($pivot, $pivot . '.' . $pivotKey, '=', $table . '.' . $secondKey)
            ->select($table . '.*')
            ->where($pivot . '.' . $firstKey, '=', $this->id);
    }
}
