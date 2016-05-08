<?php

namespace Trackit\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model implements RestrictsAccess
{
    protected $fillable = [
        'author_id',
        'body',
        'commentable_id',
        'commentable_type',
    ];

    /**
     * @var
     */
    protected $with = ['author'];

    /**
     *
     */
    public function allowsActionFrom($action, $user)
    {
        // Allow if user is author of comment
        if ($this->author_id == $user->id) {
            return true;
        }

        // Allow if user has permission to do action on the comment's parent resource
        if ($this->commentable()->allowsActionFrom($action, $user)) {
            return true;
        }

        return false;
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function commentable()
    {
        return $this->morphTo();
    }
}
