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
        // Get project statuses for this action. These are limiting.
        $projectStatuses = $user->role->accessTo($action);

        // Allow if user is part of project and has project permission
        $projectUser = $this->projectUsers()->where(['user_id' => $user->id])->first();
        if ($projectUser && $projectUser->can($action)) {
            // If action is constrained by statuses, check them
            if (sizeof($projectStatuses) > 0) {
                if (in_array($this->status, $projectStatuses)) {
                    return true;
                } else {
                    return false;
                }
            }

            return true;
        }

        // We need to do this so that default if no status defined is all statuses
        $globalStatuses = $user->role->accessTo('global:'.$action);
        $globalStatuses = sizeof($globalStatuses) > 0 ? $globalStatuses : Project::STATUSES;
        // Allow if user has global permission
        if ($user->can($action) && in_array($this->status, $globalStatuses)) {
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
