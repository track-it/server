<?php

namespace Trackit\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectPermission extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Get the relationship between the project permission and
     * its related project roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function projectRoles()
    {
    	return $this->belongsToMany(ProjectRole::class, 'project_permission_role');
    }
}
