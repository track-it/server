<?php

namespace Trackit\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public function scopeByName($query, $name)
    {
        return $query->where('name', $name);
    }

    public function can($action)
    {
        return !! $this->permissions()
                       ->where('name', $action)
                       ->first();
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function givePermissionTo($action)
    {
        $permissionId = Permission::where('name', $action)->first()->id;
        $this->permissions()->attach($permissionId);
    }

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
