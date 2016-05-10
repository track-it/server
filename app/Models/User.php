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
        'username',
        'password',
        'api_token',
        'role_id',
    ];

    protected $hidden = [
        'password'
    ];

    /**
     * @var
     */
    protected $appends = [
        'projects',
    ];

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
     *
     */
    public function getProjectsAttribute()
    {
        $projects = $this->manyThroughMany(Project::class, ProjectUser::class, 'user_id', 'id', 'project_id');
        $projects->select('projects.*', 'project_roles.name AS my_role');
        $projects->join('project_roles', 'project_roles.id', '=', 'project_users.project_role_id');

        return $projects->get();
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

    public function joinTeam($team)
    {
        $this->teams()->attach($team);
    }

    public function leaveTeam($team)
    {
        $this->teams()->detach($team);
        if ($team->fresh()->users->count() == 0) {
            $team->delete();
        }
    }

    /**
     *
     */
    public function can($permission)
    {
        return $this->role->can($permission);
    }

    public function proposals()
    {
        return $this->hasMany(Proposal::class, 'author_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'user_teams');
    }

    public function supervisor()
    {
        return $this->belongsToMany(Project::class, 'project_supervisor');
    }

    public function projectUsers()
    {
        return $this->hasMany(ProjectUser::class)->with(['project', 'projectRole']);
    }

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
