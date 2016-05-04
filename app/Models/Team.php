<?php

namespace Trackit\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{

    protected $fillable = [
        'proposal_id',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_teams');
    }

    public function project()
    {
        return $this->hasOne(Project::class);
    }

    /**
     *
     */
    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }
}
