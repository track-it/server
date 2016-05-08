<?php

namespace Trackit\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectUser extends Model
{
    /**
     * @var
     */
    protected $fillable = [
        'user_id',
        'project_id',
        'project_role_id',
    ];

    /**
     *
     */
    public function can($action)
    {
        return $this->projectRole->can($action);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function projectRole()
    {
        return $this->belongsTo(ProjectRole::class);
    }
}
