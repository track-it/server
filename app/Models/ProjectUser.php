<?php

namespace Trackit\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectUser extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
