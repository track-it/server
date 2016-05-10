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

        // Or if user has global permission, and access according to proposal status
        $statuses = $user->role->accessTo($action);
        $statuses = sizeof($statuses) > 0 ? $statuses : Proposal::STATUSES;
        if ($user->can($action) && in_array($this->status, $statuses)) {
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
