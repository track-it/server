<?php

namespace Trackit\Models;

use Illuminate\Database\Eloquent\Model;

use Trackit\Contracts\RestrictsAccess;

class Team extends Model implements RestrictsAccess
{
    protected $fillable = [
        'proposal_id',
    ];

    /**
     *
     */
    public function allowsActionFrom($action, $user)
    {
        if ($this->project->allowsActionFrom($action, $user)) {
            return true;
        }
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_teams');
    }

    public function project()
    {
        return $this->hasOne(Project::class, 'team_id');
    }

    /**
     *
     */
    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }
}
