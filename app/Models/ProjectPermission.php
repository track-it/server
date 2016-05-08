<?php

namespace Trackit\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectPermission extends Model
{
    protected $fillable = [
        'name',
    ];

    public function projectRoles()
    {
    	return $this->belongsToMany(ProjectRole::class, 'project_permission_role');
    }
}
