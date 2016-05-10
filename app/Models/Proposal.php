<?php

namespace Trackit\Models;

use Trackit\Contracts\Taggable;
use Trackit\Contracts\Commentable;
use Trackit\Contracts\Attachmentable;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model implements Attachmentable, Taggable, Commentable
{
    /**
     * @var int
     */
    const NOT_REVIEWED = 1;

    /**
     * @var int
     */
    const UNDER_REVIEW = 2;

    /**
     * @var int
     */
    const NOT_APPROVED = 3;

    /**
     * @var int
     */
    const APPROVED = 4;

    /**
     * @var int
     */
    const ARCHIVED = 5;

    /**
     * @var array
     */
    const STATUSES = [
        self::NOT_REVIEWED,
        self::UNDER_REVIEW,
        self::NOT_APPROVED,
        self::APPROVED,
        self::ARCHIVED,
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'author_id',
        'status',
    ];

    /**
     * Get the proposal's id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the relationship between the proposal and its
     * author.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Get the relationship between the proposal and its
     * attachments.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachmentable');
    }

    /**
     * Get the relationship between the proposal and its
     * tags.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    /**
     * Get the relationship between the proposal and its
     * comments.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * Get the relationship between the proposal and all
     * projects that has been created from it.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Get the relationship between the proposal and all
     * student groups that has shown interest for it.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function interestedGroups()
    {
        return $this->belongsToMany(Group::class);
    }

    /**
     * Get the relationship between the proposal and the
     * course that is connected to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the relationship between the proposal and its
     * teams.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function teams()
    {
        return $this->hasMany(Team::class);
    }
}
