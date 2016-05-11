<?php

namespace Trackit\Models;

use Trackit\Contracts\Commentable;
use Trackit\Contracts\RestrictsAccess;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model implements RestrictsAccess
{
    /**
     * @var array
     */
    protected $fillable = [
        'author_id',
        'body',
        'commentable_id',
        'commentable_type',
    ];

    /**
     * @var array
     */
    protected $with = [
        'author',
    ];

    /**
     * {@inheritdoc}
     */
    public function allowsActionFrom($action, $user)
    {
        // Allow if user is author of comment
        if ($this->author_id == $user->id) {
            return true;
        }

        // Allow if user has permission to do action on the comment's parent resource
        if ($this->commentable->allowsActionFrom($action, $user)) {
            return true;
        }

        return false;
    }

    /**
     * Get the relationship between the comment and its
     * author.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Get the relationship between the comment and its
     * commentable model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function commentable()
    {
        return $this->morphTo();
    }
}
