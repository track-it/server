<?php

namespace Trackit\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'proposal_id',
    ];

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
        return $this->hasOne(Project::class);
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
