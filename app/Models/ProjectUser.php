<?php

namespace Trackit\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProjectUser extends Pivot
{
    protected $table = 'project_user';

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'project_id',
        'project_role_id',
    ];

    /**
     * Check if the project user can perform the
     * given access.
     *
     * @param  string  action
     * @return boolean
     */
    public function can($action)
    {
        return $this->projectRole->can($action);
    }

    /**
     * Get the relationship between the project user
     * and its user model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the relationship between the project user
     * and its project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the relationship between the project user
     * and its project role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function projectRole()
    {
        return $this->belongsTo(ProjectRole::class);
    }
}
