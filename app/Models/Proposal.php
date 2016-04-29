<?php

namespace Trackit\Models;

use Illuminate\Database\Eloquent\Model;
use Trackit\Contracts\Taggable;
use Trackit\Contracts\Attachmentable;

class Proposal extends Model implements Attachmentable, Taggable
{
    const NOT_REVIEWED = 1;
    const UNDER_REVIEW = 2;
    const NOT_APPROVED = 3;
    const APPROVED = 4;

    const STATUSES = [
        self::NOT_REVIEWED,
        self::UNDER_REVIEW,
        self::NOT_APPROVED,
        self::APPROVED,
    ];

    protected $fillable = [
        'title',
        'description',
        'user_id',
        'status',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class);
    }
    
    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'source');
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

    public function getId()
    {
        return $this->id;
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
