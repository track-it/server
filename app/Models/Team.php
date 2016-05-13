<?php

namespace Trackit\Models;

use Trackit\Contracts\RestrictsAccess;
use Illuminate\Database\Eloquent\Model;

class Team extends Model implements RestrictsAccess
{
    /**
     * @var array
     */
    protected $fillable = [
        'proposal_id',
    ];

    /**
     * {@inheritdoc}
     */
    public function allowsActionFrom($action, $user)
    {
        if ($this->proposal->allowsActionFrom($action, $user)) {
            return true;
        }
    }

    /**
     * Get the relationship between the team and all
     * of its users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_teams');
    }

    /**
     * Get the relationship between the team and its
     * project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function project()
    {
        return $this->hasOne(Project::class, 'team_id');
    }

    /**
     * Get the relationship between the team and its
     * proposal.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }
}
