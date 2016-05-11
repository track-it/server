<?php

namespace Trackit\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * Defines a scope to query a role by its
     * name.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string  $name
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByName($query, $name)
    {
        return $query->where('name', $name);
    }

    /**
     * Check if a role can perform the given action.
     *
     * @param  string  $action
     * @return boolean
     */
    public function can($action)
    {
        return !! $this->permissions()
                       ->where('name', $action)
                       ->first();
    }

    /**
     * Get the relationship between the role and its
     * permissions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
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
        $permissionId = Permission::where('name', $action)->first()->id;
        $this->permissions()->attach($permissionId);
    }

    /**
     * Removes the permission to perform the given action
     * from the role.
     *
     * @param  string  $action
     * @return void
     */
    public function removePermissionTo($action)
    {
        $permissionId = Permission::where('name', $action)->first()->id;
        $this->permissions()->detach($permissionId);
    }

    public function accessTo($permission)
    {
        $accesses = $this->hasMany(Access::class)->where(['permission' => $permission])->get();

        return $accesses->map(function ($access) {
            return $access->status;
        })->toArray();
    }
}
