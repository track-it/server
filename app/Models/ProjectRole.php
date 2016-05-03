<?php

namespace Trackit\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectRole extends Model
{
    public function scopeByName($query, $name)
    {
        return $query->where('name', $name);
    }

    public function can($action)
    {
        return !! $this->projectPermissions()
                       ->where('name', $action)
                       ->first();
    }

    public function projectPermissions()
    {
        return $this->belongsToMany(ProjectPermission::class, 'project_permission_role');
    }

    public function givePermissionTo($action)
    {
        $permissionId = ProjectPermission::where('name', $action)->first()->id;
        $this->projectPermissions()->attach($permissionId);
    }

    public function removePermissionTo($action)
    {
        $permissionId = ProjectPermission::where('name', $action)->first()->id;
        $this->projectPermissions()->detach($permissionId);
    }
}
