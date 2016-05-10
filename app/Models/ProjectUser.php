<?php

namespace Trackit\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectUser extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'project_id',
        'project_role_id',
    ];

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
