<?php

namespace Trackit\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
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
        $this->morphTo();
    }
}
