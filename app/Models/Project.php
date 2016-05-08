<?php

namespace Trackit\Models;

use Illuminate\Database\Eloquent\Model;
use Trackit\Contracts\Attachmentable;

class Project extends Model implements Attachmentable, RestrictsAccess
{
    const COMPLETED = 1;
    const NOT_COMPLETED = 2;

    const STATUSES = [
        self::COMPLETED,
        self::NOT_COMPLETED,
    ];

    protected $fillable = [
        'name',
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

        $projectUser = $this->projectUsers()->where(['user_id' => $user->id])->first();

        // Allow is user is part of project and has project permission
        if ($projectUser && $projectUser->can($action)) {
            return true;
        }

        // Allow if user has global permission
        if ($user->can($action)) {
            return true;
        }

        return false;
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
