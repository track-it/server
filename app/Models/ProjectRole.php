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
        return $this->hasMany(ProjectPermission::class);
    }
}
