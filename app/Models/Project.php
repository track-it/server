<?php

namespace Trackit\Models;

use Illuminate\Database\Eloquent\Model;

use Trackit\Contracts\Attachmentable;
use Trackit\Contracts\Commentable;
use Trackit\Contracts\Taggable;
use Trackit\Models\ProjectRole;
use Trackit\Models\ProjectUser;


class Project extends Model implements Attachmentable, Commentable, Taggable
{
    const COMPLETED = 1;
    const NOT_COMPLETED = 2;

    const STATUSES = [
        self::COMPLETED,
        self::NOT_COMPLETED,
    ];

    protected $fillable = [
        'title',
        'status',
        'team_id',
        'proposal_id',
    ];

    public function getId()
    {
        return $this->id;
    }

    /**
     *
     */
    public function addProjectUser($role, $user)
    {
        $projectUser = ProjectUser::create([
            'user_id' => $user->id,
            'project_role_id' => ProjectRole::byName($role)->first()->id,
            'project_id' => $this->id,
        ]);

        return $projectUser;
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachmentable');
    }

    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function workflow()
    {
        return $this->hasOne(Workflow::class);
    }

    public function projectUsers()
    {
        return $this->hasMany(ProjectUser::class);
    }

    /**
     *
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
