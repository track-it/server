<?php

namespace Trackit\Models;

use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
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

    public function creator()
    {
        return $this->belongsTo(User::class);
    }
    
    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'source');
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function interestedGroups()
    {
        return $this->belongsToMany(Group::class);
    }
}
