<?php

namespace Trackit\Models;

use Illuminate\Database\Eloquent\Model;

use Trackit\Contracts\Attachmentable;
use Trackit\Contracts\Commentable;
use Trackit\Contracts\Taggable;
use Trackit\Contracts\RestrictsAccess;
use Trackit\Models\ProjectRole;
use Trackit\Models\ProjectUser;

class Project extends Model implements Attachmentable, Commentable, Taggable, RestrictsAccess
{
    const NOT_COMPLETED = 1;
    const COMPLETED = 2;
    const PUBLISHED = 3;

    const STATUSES = [
        self::NOT_COMPLETED,
        self::COMPLETED,
        self::PUBLISHED,
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

    public function allowsActionFrom($action, $user)
    {
        // Allow if user is part of project and has project permission
        $projectUser = $this->projectUsers()->where(['user_id' => $user->id])->first();
        if ($projectUser && $projectUser->can($action)) {
            return true;
        }

        // Allow if user has global permission
        $statuses = $user->role->accessTo($action);
        $statuses = sizeof($statuses) > 0 ? $statuses : Project::STATUSES;
        if ($user->can($action) && in_array($this->status, $statuses)) {
            return true;
        }

        return false;
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
