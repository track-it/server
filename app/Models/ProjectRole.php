<?php

namespace Trackit\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectRole extends Model
{
    /**
     * @var aray
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Defines a scope to query a role by its name.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $name
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByName($query, $name)
    {
        return $query->where('name', $name);
    }

    /**
     * Check if the permission can perform a given
     * action.
     *
     * @param  string  $action
     * @return boolean
     */
    public function can($action)
    {
        return !! $this->projectPermissions()
                       ->where('name', $action)
                       ->first();
    }

    /**
     * Gives the role permission to perform the given
     * action.
     *
     * @param  string  $action
     * @return void
     */
    public function givePermissionTo($action)
    {
        $permissionId = ProjectPermission::where('name', $action)->first()->id;
        $this->projectPermissions()->attach($permissionId);
    }

    /**
     * Removes the permission to perform a given action
     * from the role.
     *
     * @param  string  $action
     * @return void
     */
    public function removePermissionTo($action)
    {
        $permissionId = ProjectPermission::where('name', $action)->first()->id;
        $this->projectPermissions()->detach($permissionId);
    }

    /**
     * Get the relationship between the project role and
     * its permissions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function projectPermissions()
    {
        return $this->belongsToMany(ProjectPermission::class, 'project_permission_role');
    }

    /**
     * Get the relationship between the project role and
     * its project users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function projectUsers()
    {
        return $this->hasMany(ProjectUser::class);
    }
}
