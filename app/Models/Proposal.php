<?php

namespace Trackit\Models;

use Trackit\Contracts\Taggable;
use Trackit\Contracts\Searchable;
use Trackit\Contracts\Commentable;
use Trackit\Contracts\Attachmentable;
use Trackit\Contracts\RestrictsAccess;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model implements Attachmentable, Taggable, Searchable, Commentable, RestrictsAccess
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
     * @var array
     */
    protected $with = [
        'author',
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
     * Get searchresult
     * @param $string search string
     * @return collection
     */
    public static function search($string, $user, $statuses)
    {
        return self::where('title', 'like', "%$string%")
            ->orWhereHas('tags', function ($query) use ($string) {
                $query->where('name', 'LIKE', "%$string%");
            })
            ->get()
            ->filter(function ($proposal) use ($statuses, $user) {
                return in_array($proposal->status, $statuses)
                    || $proposal->author_id == $user->id;
            });
    }

    /**
     * {@inheritdoc}
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
