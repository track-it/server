<?php

namespace Trackit\Models;

use Illuminate\Database\Eloquent\Model;
use Trackit\Contracts\Commentable;
use Trackit\Contracts\Taggable;
use Trackit\Contracts\Attachmentable;
use Trackit\Contracts\RestrictsAccess;

class Proposal extends Model implements Attachmentable, Taggable, Commentable, RestrictsAccess
{
    const NOT_REVIEWED = 1;
    const UNDER_REVIEW = 2;
    const NOT_APPROVED = 3;
    const APPROVED = 4;
    const ARCHIVED = 5;

    const STATUSES = [
        self::NOT_REVIEWED,
        self::UNDER_REVIEW,
        self::NOT_APPROVED,
        self::APPROVED,
        self::ARCHIVED,
    ];

    protected $fillable = [
        'title',
        'description',
        'author_id',
        'status',
    ];

    public function getId()
    {
        return $this->id;
    }

    /**
     *
     */
    public function allowsActionFrom($action, $user)
    {
        // Allow if user is author of proposal
        if ($user->id == $this->author_id) {
            return true;
        }

        // We need to do this so that default if no status defined is all statuses
        $globalStatuses = $user->role->accessTo('global:'.$action);
        $globalStatuses = sizeof($globalStatuses) > 0 ? $globalStatuses : Proposal::STATUSES;
        // Allow if user has global permission
        if ($user->can($action) && in_array($this->status, $globalStatuses)) {
            return true;
        }

        return false;
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachmentable');
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function interestedGroups()
    {
        return $this->belongsToMany(Group::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     *
     */
    public function teams()
    {
        return $this->hasMany(Team::class);
    }
}
